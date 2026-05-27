<?php

namespace App\Models;

use App\Classes\Traits\PersianDate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    use HasFactory, PersianDate;

    protected $guarded = [];

    public $timestamps = true;

    # region Const
    const PENDING = 'pending';
    const CONFIRMED = 'confirmed';
    const STATUES = [
        self::PENDING => [
            'value' => self::PENDING,
            'color' => 'warning',
            'fa_text' => 'در انتظار پرداخت'
        ],
        self::CONFIRMED => [
            'value' => self::CONFIRMED,
            'color' => 'success',
            'fa_text' => 'پرداخت شد'
        ]
    ];
    # endregion

    # region Methods
    public function status($index = 'fa_text'): string
    {
        return self::STATUES[$this->status][$index];
    }

    public static function pendingBadgeCount(): int
    {
        return static::query()->where('status', self::PENDING)->count();
    }

    public function scopeOrderedForAdmin(Builder $query): Builder
    {
        return $query
            ->orderByRaw('CASE WHEN status = ? THEN 0 ELSE 1 END', [self::PENDING])
            ->latest('created_at');
    }
    # endregion

    # region Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    # endregion
}
