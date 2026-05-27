<?php

namespace App\Models;

use App\Classes\Traits\PersianDate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HostPayout extends Model
{
    use HasFactory, PersianDate;

    protected $guarded = [];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    const PENDING = 'pending';
    const PAID = 'paid';
    const CANCELLED = 'cancelled';

    const TAB_PENDING = 'pending';
    const TAB_PAID = 'paid';
    const TAB_CANCELLED = 'cancelled';

    const TABS = [
        self::TAB_PENDING,
        self::TAB_PAID,
        self::TAB_CANCELLED,
    ];

    const STATUSES = [
        self::PENDING => [
            'value' => self::PENDING,
            'color' => 'warning',
            'fa_text' => 'در انتظار پرداخت',
        ],
        self::PAID => [
            'value' => self::PAID,
            'color' => 'success',
            'fa_text' => 'پرداخت شده',
        ],
        self::CANCELLED => [
            'value' => self::CANCELLED,
            'color' => 'danger',
            'fa_text' => 'لغو شده',
        ],
    ];

    public function status(string $index = 'fa_text'): string
    {
        return self::STATUSES[$this->status][$index];
    }

    public static function pendingBadgeCount(): int
    {
        return static::query()->where('status', self::PENDING)->count();
    }

    public static function tabCounts(int $userId): array
    {
        $rows = static::query()
            ->where('user_id', $userId)
            ->selectRaw('status, COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        return [
            self::TAB_PENDING => (int) ($rows[self::PENDING] ?? 0),
            self::TAB_PAID => (int) ($rows[self::PAID] ?? 0),
            self::TAB_CANCELLED => (int) ($rows[self::CANCELLED] ?? 0),
        ];
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::PENDING);
    }

    public function scopeForTab(Builder $query, string $tab): Builder
    {
        $status = match ($tab) {
            self::TAB_PAID => self::PAID,
            self::TAB_CANCELLED => self::CANCELLED,
            default => self::PENDING,
        };

        return $query->where('status', $status);
    }

    public function scopeOrderedForAdmin(Builder $query): Builder
    {
        return $query
            ->orderByRaw('CASE WHEN status = ? THEN 0 ELSE 1 END', [self::PENDING])
            ->latest('created_at');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
