<?php

namespace App\Models;

use App\Classes\Traits\PersianDate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

class Discount extends Model
{
    use HasFactory, PersianDate;

    protected $guarded = [];

    const PERCENT = 'percent';
    const AMOUNT = 'amount';

    const TYPES = [
        self::PERCENT => [
            'value' => self::PERCENT,
            'fa_text' => 'درصد',
            'symbol' => '٪',
        ],
        self::AMOUNT => [
            'value' => self::AMOUNT,
            'fa_text' => 'مبلغ',
            'symbol' => 'تومان',
        ],
    ];

    const NEW_USERS = 'new_users';
    const OLD_USERS = 'old_users';

    const USER_TYPES = [
        self::NEW_USERS => [
            'value' => self::NEW_USERS,
            'fa_text' => 'ثبت‌نامی جدید',
        ],
        self::OLD_USERS => [
            'value' => self::OLD_USERS,
            'fa_text' => 'کاربران',
        ],
    ];

    protected $casts = [
        'amount' => 'int',
        'expired_at' => 'datetime',
        'sms_data' => 'object',
    ];

    public function attachUsers(Request $request): Discount
    {
        if ($this->user_type === Discount::OLD_USERS) {
            $this->users()->detach();
            $value = $request->get('users');
            $count = 0;

            if ($value === 'all') {
                User::query()->latest()->chunkById(100, function ($users) use (&$count) {
                    $count += $users->count();
                    $this->users()->attach($users);
                });
            }
            if ($value === 'has_orders') {
                $start = $request->get('start_date');
                $end = $request->get('end_date');

                User::query()->latest()->whereHas('orders', function (Builder $query) use ($start, $end) {
                    $query->whereNotIn('status', [Order::PENDING, Order::CANCELED, Order::REJECTED])
                        ->whereBetween('start_at', [$start, $end]);
                })->chunkById(100, function ($users) use (&$count) {
                    $count += $users->count();
                    $this->users()->attach($users);
                });
            }
            if ($value === 'has_not_orders') {
                User::query()->latest()->whereDoesntHave('orders', function (Builder $query) use ($request) {

                    $query->whereNotIn('status', [Order::PENDING, Order::CANCELED, Order::REJECTED]);

                })->chunkById(100, function ($users) use (&$count) {
                    $count += $users->count();
                    $this->users()->attach($users);
                });
            }
            if ($value === 'owners') {
                User::query()->latest()->whereHas('homes', function (Builder $query) {
                    $query->where('status', Home::ACCEPTED);

                })->chunkById(100, function ($users) use (&$count) {
                    $count += $users->count();
                    $this->users()->attach($users);
                });
            }
            if ($value === 'selected') {
                User::query()->latest()->whereIn('id', $request->get('users_list'))->chunkById(100, function ($users) use (&$count) {
                    $count += $users->count();
                    $this->users()->attach($users);
                });
            }

            $this->update(['count_users' => $count]);
        }

        return $this;
    }

    public static function attachToNewUser(User $user): void
    {
        self::query()
            ->where('user_type', self::NEW_USERS)
            ->where('expired_at', '>', now())
            ->each(function (self $discount) use ($user) {
                if ($discount->users()->where('users.id', $user->id)->exists()) {
                    return;
                }

                $discount->users()->attach($user->id);
                $discount->increment('count_users');
            });
    }

    public function calc(int $price): int
    {
        if ($this->type === self::PERCENT) {
            return ($this->amount * $price) / 100;
        }
        if ($this->type === self::AMOUNT) {
            return ($price <= $this->amount)
                ? $price - 10000
                : $this->amount;
        }

        return 0;
    }

    public function type($index = 'fa_text'): string
    {
        return self::TYPES[$this->type][$index];
    }

    public function userType($index = 'fa_text'): string
    {
        return self::USER_TYPES[$this->user_type][$index];
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_has_discounts')->withPivot('is_used');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
