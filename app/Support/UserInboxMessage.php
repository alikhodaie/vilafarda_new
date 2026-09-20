<?php

namespace App\Support;

use Carbon\Carbon;
use Illuminate\Support\Str;

class UserInboxMessage
{
    public const TYPE_NEWSLETTER = 'newsletter';

    public const TYPE_SMS = 'sms';

    public string $type;

    public int $id;

    public string $title;

    public string $preview;

    public string $body;

    public Carbon $createdAt;

    public bool $isUnread;

    public string $kindLabel;

    public bool $bodyIsHtml;

    public function __construct(
        string $type,
        int $id,
        string $title,
        string $body,
        Carbon $createdAt,
        bool $isUnread,
        string $kindLabel,
        bool $bodyIsHtml = false
    ) {
        $this->type = $type;
        $this->id = $id;
        $this->title = $title;
        $this->body = $body;
        $this->preview = Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($body))), 120);
        $this->createdAt = $createdAt;
        $this->isUnread = $isUnread;
        $this->kindLabel = $kindLabel;
        $this->bodyIsHtml = $bodyIsHtml;
    }

    public function showRoute(?string $filter = null): string
    {
        $params = ['type' => $this->type, 'id' => $this->id];

        if ($filter && $filter !== 'all') {
            $params['filter'] = $filter;
        }

        return route('dashboard.inbox.show', $params);
    }

    public function timeLabel(): string
    {
        if ($this->createdAt->isToday()) {
            return $this->createdAt->format('H:i');
        }

        if ($this->createdAt->isYesterday()) {
            return 'دیروز';
        }

        if ($this->createdAt->isCurrentYear()) {
            return $this->createdAt->format('m/d');
        }

        return $this->createdAt->format('Y/m/d');
    }
}
