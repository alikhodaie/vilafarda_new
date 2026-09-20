<?php

namespace App\Models;

use App\Classes\Traits\PersianDate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    # region traits
    use PersianDate;
    # endregion

    # region variables
    protected $guarded = [];
    # endregion

    # region Const
    const FILE_PATH = 'newsletter/';

    public const AUDIENCE_ALL = 'all';

    public const AUDIENCE_HOSTS = 'hosts';

    public const AUDIENCE_GUESTS = 'guests';

    public const AUDIENCE_ADMINS = 'admins';

    public const AUDIENCES = [
        self::AUDIENCE_ALL => 'همه کاربران',
        self::AUDIENCE_HOSTS => 'میزبان‌ها (اقامتگاه ثبت‌شده)',
        self::AUDIENCE_GUESTS => 'مهمان‌ها',
        self::AUDIENCE_ADMINS => 'مدیران',
    ];
    # endregion

    # region Methods
    public static function getDescriptionPath(): string
    {
        return self::FILE_PATH.'/description/';
    }

    public static function audienceValues(): array
    {
        return array_keys(self::AUDIENCES);
    }

    public function audienceLabel(): string
    {
        return self::AUDIENCES[$this->audience ?: self::AUDIENCE_ALL] ?? self::AUDIENCES[self::AUDIENCE_ALL];
    }

    public function isVisibleTo(User $user): bool
    {
        $audience = $this->audience ?: self::AUDIENCE_ALL;

        return match ($audience) {
            self::AUDIENCE_HOSTS => $user->isNewsletterHost(),
            self::AUDIENCE_GUESTS => $user->isNewsletterGuest(),
            self::AUDIENCE_ADMINS => $user->isAdmin(),
            default => true,
        };
    }

    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        return $query->where(function (Builder $inner) use ($user) {
            $inner->where('audience', self::AUDIENCE_ALL)
                ->orWhereNull('audience');

            if ($user->isNewsletterHost()) {
                $inner->orWhere('audience', self::AUDIENCE_HOSTS);
            }

            if ($user->isNewsletterGuest()) {
                $inner->orWhere('audience', self::AUDIENCE_GUESTS);
            }

            if ($user->isAdmin()) {
                $inner->orWhere('audience', self::AUDIENCE_ADMINS);
            }
        });
    }
    # endregion
}
