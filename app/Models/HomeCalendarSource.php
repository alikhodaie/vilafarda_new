<?php

namespace App\Models;

use App\Support\ExternalCalendarPlatform;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HomeCalendarSource extends Model
{
    public const STATUS_SUCCESS = 'success';

    public const STATUS_FAILED = 'failed';

    protected $fillable = [
        'home_id',
        'platform',
        'external_url',
        'external_room_id',
        'sync_enabled',
        'last_synced_at',
        'last_sync_status',
        'last_sync_message',
        'last_blocked_dates',
    ];

    protected $casts = [
        'sync_enabled' => 'boolean',
        'last_synced_at' => 'datetime',
        'last_blocked_dates' => 'array',
    ];

    public function home(): BelongsTo
    {
        return $this->belongsTo(Home::class);
    }

    public function platformLabel(): string
    {
        return ExternalCalendarPlatform::label($this->platform);
    }

    public function syncStatusLabel(): ?string
    {
        return match ($this->last_sync_status) {
            self::STATUS_SUCCESS => 'موفق',
            self::STATUS_FAILED => 'ناموفق',
            default => null,
        };
    }

    public function syncStatusColor(): string
    {
        return match ($this->last_sync_status) {
            self::STATUS_SUCCESS => 'success',
            self::STATUS_FAILED => 'danger',
            default => 'secondary',
        };
    }

    public function applyExternalUrl(?string $url): void
    {
        $url = trim((string) $url);

        if ($url === '') {
            $this->external_url = null;
            $this->platform = null;
            $this->external_room_id = null;

            return;
        }

        $this->external_url = $url;
        $this->platform = ExternalCalendarPlatform::detectFromUrl($url);
        $this->external_room_id = ExternalCalendarPlatform::extractRoomId($this->platform, $url);
    }
}
