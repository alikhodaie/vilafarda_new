<?php

namespace App\Models;

use App\Classes\Traits\PersianDate;
use App\Support\HomeSlug;
use App\Services\OrderAdminSmsService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, PersianDate;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_admin' => 'bool',
        'email_verified_at' => 'datetime',
        'mobile_verified_at' => 'datetime',
    ];

    # region Const
    const MAX_AVATAR_SIZE = 2048;

    const FILE_PATH = 'files/user/';

    const ADMIN_ORDER_SMS_CACHE_KEY = 'admins_order_sms';

    const ADMIN_ORDER_SMS_ALWAYS_CACHE_KEY = 'admins_order_sms_always';

    const ADMIN_ORDER_SMS_ROTATING_CACHE_KEY = 'admins_order_sms_rotating';
    # endregion

    # region Methods
    public static function forgetOrderSmsCache(): void
    {
        cache()->delete(self::ADMIN_ORDER_SMS_CACHE_KEY);
        cache()->delete(self::ADMIN_ORDER_SMS_ALWAYS_CACHE_KEY);
        cache()->delete(self::ADMIN_ORDER_SMS_ROTATING_CACHE_KEY);
    }

    public static function getAdminsThatCanGetOrdersSms(): Collection
    {
        return self::getAdminsWithAlwaysOrderSms()->merge(self::getAdminsWithRotatingOrderSms())->unique('id');
    }

    public static function getAdminsWithAlwaysOrderSms(): Collection
    {
        return cache()->rememberForever(self::ADMIN_ORDER_SMS_ALWAYS_CACHE_KEY, function () {
            return self::queryAdminsByOrderSmsMode(OrderAdminSmsService::MODE_ALWAYS)->get();
        });
    }

    public static function getAdminsWithRotatingOrderSms(): Collection
    {
        return cache()->rememberForever(self::ADMIN_ORDER_SMS_ROTATING_CACHE_KEY, function () {
            return self::queryAdminsByOrderSmsMode(OrderAdminSmsService::MODE_ROTATING)->get();
        });
    }

    protected static function queryAdminsByOrderSmsMode(string $mode): Builder
    {
        return self::query()->admin()->whereHas('roles', function ($roles) use ($mode) {
            $roles->whereHas('permissions', function ($permissions) {
                $permissions->where('name', 'orders:sms');
            });

            if ($mode === OrderAdminSmsService::MODE_ALWAYS) {
                $roles->where(function ($query) {
                    $query->where('order_sms_mode', OrderAdminSmsService::MODE_ALWAYS)
                        ->orWhereNull('order_sms_mode');
                });
            } else {
                $roles->where('order_sms_mode', OrderAdminSmsService::MODE_ROTATING);
            }
        })->orderBy('id');
    }

    public function rent(Home $home, Carbon $start_date, Carbon $end_date, int $main_guest, int $extra_guest, int $consultant_id = null)
    {
        $price = $home->calcPrice($start_date, $end_date, $extra_guest);

        $status = ($home->hasFastReserve($start_date, $end_date))
            ? Order::AWAITING_PAYMENT
            : Order::PENDING;

        $accepted_at = ($status === Order::AWAITING_PAYMENT)
            ? now()
            : null;

        return $this->rents()->create([
            'home_id' => $home->id,
            'user_id' => $home->user_id,
            'start_at' => $start_date,
            'end_at' => $end_date,
            'price' => $price,
            'main_guest' => $main_guest,
            'extra_guest' => $extra_guest,
            'consultant_id' => $consultant_id,
            'accepted_at' => $accepted_at,
            'status' => $status
        ]);
    }

    public function getAvatarPath(): string
    {
        return self::FILE_PATH.$this->id.'/';
    }

    /**
     * Update user avatar
     *
     * @param UploadedFile $avatar
     */
    public function updateAvatar(UploadedFile $avatar)
    {
        $this->deleteAvatar();
        $avatar = $avatar->store($this->getAvatarPath());

        $this->update(['avatar' => basename($avatar)]);
    }

    /**
     * Remove avatar from user
     */
    public function removeAvatar()
    {
        $this->deleteAvatar();
        $this->update(['avatar' => null]);
    }

    /**
     * Delete avatar File
     *
     * @return bool
     */
    public function deleteAvatar(): bool
    {
        if ($this->avatar){
            Storage::delete($this->getAvatarPath().$this->avatar);
        }

        return true;
    }

    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    public function isBlocked(): bool
    {
        return $this->is_block;
    }

    public static function getDefaultAvatar(): string
    {
        return asset(self::FILE_PATH.'avatar.jpg');
    }

    public function isRent(Home $home): bool
    {
        return $home->orders()
            ->whereIn('status', [Order::DONE, Order::IN_RENT])
            ->where('renter_id', $this->id)
            ->exists();
    }
    # endregion

    # region Scopes

    /**
     * Filter User who is Admin
     *
     * @param $query
     * @return mixed
     */
    public function scopeAdmin($query)
    {
        return $query->where('is_admin', true);
    }

    /**
     * Search in user scope
     *
     * @param $query
     * @return mixed
     */
    public function scopeSearch($query)
    {
        if (request()->filled('id')){
            $query->where('id', request()->get('id'));
        }
        if (request()->filled('name')){
            $name = request('name');
            $query->where(DB::raw('CONCAT(first_name, " ", last_name)'), 'LIKE', "%$name%");

            $query->where(function (Builder $builder) use ($name){
                foreach (explode(' ', $name) as $spliced_name){
                    $builder->orWhere('first_name', 'LIKE', "%$spliced_name%")
                        ->orWhere('last_name', 'LIKE', "%$spliced_name%");
                }
            });
        }
        if (request()->filled('email')){
            $query->where('email', 'LIKE', '%'.request()->get('email').'%');
        }
        if (request()->filled('mobile')){
            $query->where('mobile', request()->get('mobile'));
        }
        if (request()->filled('discount')){
            $query->whereHas('discounts', function ($discounts) {
                $discounts->where('discounts.id', request('discount'))
                    ->where('users_has_discounts.is_used', false);
            });
        }
        if (request()->filled('role')){
            $query->whereHas('roles', function (Builder $builder){

                $builder->where('id', request()->get('role'));
            });
        }
        if (request()->filled('blocked')){
            switch (request()->get('blocked')){
                case 'yes';
                    $query->where('is_block', true);
                    break;

                case 'no':
                    $query->where('is_block', false);
                    break;
            }
        }
        if (request()->filled('verified_email')){
            switch (request()->get('verified_email')){
                case 'yes';
                    $query->whereNotNull('email_verified_at');
                    break;

                case 'no':
                    $query->whereNull('email_verified_at');
                    break;
            }
        }
        if (request()->filled('verified_mobile')){
            switch (request()->get('verified_mobile')){
                case 'yes';
                    $query->whereNotNull('mobile_verified_at');
                    break;

                case 'no':
                    $query->whereNull('mobile_verified_at');
                    break;
            }
        }

        return $query;
    }
    # endregion

    # region Accessories
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getAvatarPathAttribute(): string
    {
        return ($this->avatar)
            ? asset($this->getAvatarPath().$this->avatar)
            : self::getDefaultAvatar();
    }

    public function getRoleAttribute(): string
    {
        return $this->roles->first()->fa_name;
    }
    # endregion

    # region Relations
    public function articles()
    {
        return $this->hasMany(Article::class, 'author_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function homes()
    {
        return $this->hasMany(Home::class);
    }

    public function findHomeOrFail(mixed $home, array $with = []): Home
    {
        $query = $this->homes();

        if ($with !== []) {
            $query->with($with);
        }

        return $query->whereKey(HomeSlug::resolveKeyForQuery($home))->firstOrFail();
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Return rent orders for renter
     *
     * @return HasMany
     */
    public function rents()
    {
        return $this->hasMany(Order::class, 'renter_id');
    }

    /**
     * Return rent orders for owner
     *
     * @return HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function withdraws()
    {
        return $this->hasMany(Withdraw::class);
    }

    public function hostPayouts()
    {
        return $this->hasMany(HostPayout::class);
    }

    public function discounts(): BelongsToMany
    {
        return $this->belongsToMany(Discount::class, 'users_has_discounts')->withPivot('is_used');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
    # endregion
}
