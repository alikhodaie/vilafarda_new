<?php

namespace App\Models;

use App\Classes\Traits\PersianDate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SmsLog extends Model
{
    use PersianDate;

    public const STATUS_SENT = 'sent';

    public const STATUS_FAILED = 'failed';

    public const STATUS_SKIPPED = 'skipped';

    protected $guarded = [];

    protected $casts = [
        'parameters' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function related(): MorphTo
    {
        return $this->morphTo();
    }

    public function isSent(): bool
    {
        return $this->status === self::STATUS_SENT;
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_SENT => __('title.sms_log_status_sent'),
            self::STATUS_FAILED => __('title.sms_log_status_failed'),
            self::STATUS_SKIPPED => __('title.sms_log_status_skipped'),
            default => $this->status,
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            self::STATUS_SENT => 'bg-success',
            self::STATUS_FAILED => 'bg-danger',
            self::STATUS_SKIPPED => 'bg-secondary',
            default => 'bg-warning',
        };
    }
}
