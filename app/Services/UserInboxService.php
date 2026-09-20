<?php

namespace App\Services;

use App\Models\Home;
use App\Models\InboxRead;
use App\Models\Newsletter;
use App\Models\Order;
use App\Models\SmsLog;
use App\Models\User;
use App\Support\SmsTemplates;
use App\Support\UserInboxMessage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Collection;

class UserInboxService
{
    public const TYPE_NEWSLETTER = UserInboxMessage::TYPE_NEWSLETTER;

    public const TYPE_SMS = UserInboxMessage::TYPE_SMS;

    /** @var array<int, int> */
    private array $unreadCache = [];

    public function unreadCount(User $user): int
    {
        return $this->unreadCache[$user->id] ??= $this->computeUnreadCount($user);
    }

    public function paginate(User $user, int $perPage = 15, string $filter = 'all'): LengthAwarePaginator
    {
        $items = $this->filteredMessages($user, $filter);
        $page = Paginator::resolveCurrentPage();

        return new Paginator(
            $items->forPage($page, $perPage)->values(),
            $items->count(),
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );
    }

    public function find(User $user, string $type, int $id): UserInboxMessage
    {
        $message = $this->allMessages($user)->first(
            fn (UserInboxMessage $item) => $item->type === $type && $item->id === $id
        );

        if (! $message) {
            abort(404);
        }

        return $message;
    }

    public function markRead(User $user, string $type, int $id): void
    {
        $readableType = $this->readableType($type);

        InboxRead::query()->firstOrCreate([
            'user_id' => $user->id,
            'readable_type' => $readableType,
            'readable_id' => $id,
        ]);

        unset($this->unreadCache[$user->id]);
    }

    public function markAllRead(User $user): int
    {
        $unread = $this->allMessages($user)->filter(fn (UserInboxMessage $message) => $message->isUnread);
        $now = now();
        $rows = [];

        foreach ($unread as $message) {
            $rows[] = [
                'user_id' => $user->id,
                'readable_type' => $this->readableType($message->type),
                'readable_id' => $message->id,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if ($rows !== []) {
            InboxRead::query()->insertOrIgnore($rows);
        }

        unset($this->unreadCache[$user->id]);

        return $unread->count();
    }

    /**
     * @return Collection<int, UserInboxMessage>
     */
    public function filteredMessages(User $user, string $filter = 'all'): Collection
    {
        $items = $this->allMessages($user);

        return $items->filter(function (UserInboxMessage $message) use ($filter) {
            if ($filter === 'unread') {
                return $message->isUnread;
            }

            if ($filter === 'sms') {
                return $message->type === UserInboxMessage::TYPE_SMS;
            }

            if ($filter === 'newsletter') {
                return $message->type === UserInboxMessage::TYPE_NEWSLETTER;
            }

            return true;
        })->values();
    }

    /**
     * @return Collection<int, UserInboxMessage>
     */
    public function allMessages(User $user): Collection
    {
        $readKeys = InboxRead::query()
            ->where('user_id', $user->id)
            ->get()
            ->map(fn (InboxRead $read) => $read->readable_type.':'.$read->readable_id)
            ->all();

        $newsletters = $this->newsletterQuery($user)
            ->latest()
            ->get()
            ->map(fn (Newsletter $newsletter) => $this->fromNewsletter($newsletter, $readKeys));

        $smsLogs = $this->userSmsQuery($user)
            ->latest()
            ->get()
            ->map(fn (SmsLog $smsLog) => $this->fromSms($smsLog, $readKeys));

        return $newsletters
            ->concat($smsLogs)
            ->sortByDesc(fn (UserInboxMessage $message) => $message->createdAt->timestamp)
            ->values();
    }

    public static function isInboxSms(string $patternId): bool
    {
        return SmsTemplates::isInboxVisible($patternId);
    }

    public static function renderSmsBody(SmsLog $smsLog): string
    {
        $parameters = self::normalizeParameters($smsLog->parameters);

        if (isset($parameters['message']) && trim((string) $parameters['message']) !== '') {
            return trim((string) $parameters['message']);
        }

        $template = SmsTemplates::findForInbox((string) $smsLog->pattern_id, $smsLog->pattern_title);
        $parameters = self::enrichInboxParameters($smsLog, $template, $parameters);
        $inboxBody = trim((string) ($template['inbox_body'] ?? ''));

        if ($inboxBody !== '') {
            return self::replacePlaceholders($inboxBody, $parameters);
        }

        $description = trim((string) ($template['description'] ?? ''));

        if ($description !== '' && preg_match('/%[A-Z0-9_-]+%/', $description)) {
            return self::replacePlaceholders($description, $parameters);
        }

        $title = trim((string) ($smsLog->pattern_title ?: ($template['title'] ?? '')));

        return $title !== '' ? $title : 'پیام ویلا فردا';
    }

    /**
     * @param  array<int|string, mixed>|null  $parameters
     * @return array<string, string>
     */
    public static function normalizeParameters($parameters): array
    {
        if (! is_array($parameters) || $parameters === []) {
            return [];
        }

        if (isset($parameters[0]) && is_array($parameters[0]) && array_key_exists('name', $parameters[0])) {
            $normalized = [];

            foreach ($parameters as $item) {
                if (! is_array($item) || ! isset($item['name'])) {
                    continue;
                }

                $normalized[(string) $item['name']] = (string) ($item['value'] ?? '');
            }

            return self::expandParameterAliases($normalized);
        }

        $normalized = [];

        foreach ($parameters as $key => $value) {
            if (is_array($value)) {
                continue;
            }

            $normalized[(string) $key] = (string) $value;
        }

        return self::expandParameterAliases($normalized);
    }

    /**
     * @param  array<string, string>  $parameters
     * @return array<string, string>
     */
    public static function expandParameterAliases(array $parameters): array
    {
        $expanded = $parameters;

        foreach ($parameters as $key => $value) {
            $upper = strtoupper((string) $key);
            $hyphen = str_replace('_', '-', $upper);
            $underscore = str_replace('-', '_', $upper);

            foreach ([$upper, $hyphen, $underscore] as $alias) {
                if ($alias !== '' && ! isset($expanded[$alias])) {
                    $expanded[$alias] = $value;
                }
            }
        }

        return $expanded;
    }

    /**
     * @param  array<string, mixed>|null  $template
     * @param  array<string, string>  $parameters
     * @return array<string, string>
     */
    public static function enrichInboxParameters(SmsLog $smsLog, ?array $template, array $parameters): array
    {
        $key = (string) ($template['key'] ?? '');

        if ($key === 'order_created_renter') {
            $parameters = self::enrichConsultantParameters($parameters);
        }

        $parameters = self::enrichInboxLinks($smsLog, $parameters);

        return self::expandParameterAliases($parameters);
    }

    /**
     * @param  array<string, string>  $parameters
     * @return array<string, string>
     */
    private static function enrichConsultantParameters(array $parameters): array
    {
        $hasName = trim((string) ($parameters['CONSULTANT_NAME'] ?? '')) !== '';
        $hasMobile = trim((string) ($parameters['CONSULTANT_MOBILE'] ?? '')) !== '';

        if ($hasName && $hasMobile) {
            return $parameters;
        }

        $consultant = app(OrderAdminSmsService::class)->resolveGuestConsultantAdmin(null);

        if (! $consultant) {
            return $parameters;
        }

        if (! $hasName && filled($consultant->full_name)) {
            $parameters['CONSULTANT_NAME'] = trim($consultant->full_name);
        }

        if (! $hasMobile && filled($consultant->mobile)) {
            $parameters['CONSULTANT_MOBILE'] = trim((string) $consultant->mobile);
        }

        return $parameters;
    }

    /**
     * @param  array<string, string>  $parameters
     * @return array<string, string>
     */
    private static function enrichInboxLinks(SmsLog $smsLog, array $parameters): array
    {
        $order = self::resolveInboxOrder($smsLog, $parameters);
        $home = self::resolveInboxHome($smsLog, $order);

        $orderUrl = $order ? route('dashboard.orders.show', $order) : '';
        $rentUrl = $order ? route('dashboard.rents.show', $order) : '';
        $payUrl = $order ? route('dashboard.rents.pay', $order) : '';
        $reviewUrl = $order ? route('dashboard.rents.review.create', $order) : '';
        $homeUrl = $home ? route('dashboard.homes.edit', $home) : '';
        $homesIndexUrl = route('dashboard.homes.index');
        $searchUrl = route('main.homes.index');
        $calendarUrl = self::resolveCalendarUrl($parameters, $order, $home);

        $isHostRecipient = $order && (int) $smsLog->user_id === (int) $order->user_id;
        $actionUrl = $isHostRecipient ? $orderUrl : $rentUrl;

        $parameters['ORDER_LINK'] = $orderUrl;
        $parameters['RENT_LINK'] = $rentUrl;
        $parameters['PAY_LINK'] = $payUrl;
        $parameters['REVIEW_LINK'] = $reviewUrl;
        $parameters['HOME_LINK'] = $homeUrl;
        $parameters['HOMES_LINK'] = $homesIndexUrl;
        $parameters['SEARCH_LINK'] = $searchUrl;
        $parameters['CALENDAR_URL'] = $calendarUrl;
        $parameters['ACTION_LINK'] = $actionUrl ?: ($orderUrl ?: $rentUrl);

        $parameters['ORDER_LINK_LINE'] = $orderUrl !== ''
            ? "\nبرای تأیید یا رد درخواست، این لینک را باز کنید:\n".$orderUrl
            : '';
        $parameters['RENT_LINK_LINE'] = $rentUrl !== ''
            ? "\nجزئیات رزرو:\n".$rentUrl
            : '';
        $parameters['PAY_LINK_LINE'] = $payUrl !== ''
            ? "\nبرای پرداخت، این لینک را باز کنید:\n".$payUrl
            : '';
        $parameters['REVIEW_LINK_LINE'] = $reviewUrl !== ''
            ? "\nبرای ثبت نظر، این لینک را باز کنید:\n".$reviewUrl
            : '';
        $parameters['HOME_LINK_LINE'] = $homeUrl !== ''
            ? "\nمشاهده اقامتگاه:\n".$homeUrl
            : '';
        $parameters['HOMES_LINK_LINE'] = "\nاقامتگاه‌های من:\n".$homesIndexUrl;
        $parameters['SEARCH_LINK_LINE'] = "\nجستجوی اقامتگاه:\n".$searchUrl;
        $parameters['CALENDAR_LINK_LINE'] = $calendarUrl !== ''
            ? "\nباز کردن تقویم:\n".$calendarUrl
            : '';
        $parameters['ACTION_LINK_LINE'] = ($parameters['ACTION_LINK'] ?? '') !== ''
            ? "\nمشاهده جزئیات:\n".$parameters['ACTION_LINK']
            : '';

        return $parameters;
    }

    /**
     * @param  array<string, string>  $parameters
     */
    private static function resolveInboxOrder(SmsLog $smsLog, array $parameters): ?Order
    {
        $related = $smsLog->related;

        if ($related instanceof Order) {
            return $related;
        }

        $userId = (int) $smsLog->user_id;
        if ($userId <= 0) {
            return null;
        }

        $code = trim((string) ($parameters['ID'] ?? ''));
        $query = Order::query()->where(function ($inner) use ($userId) {
            $inner->where('user_id', $userId)->orWhere('renter_id', $userId);
        });

        if ($code !== '') {
            $byCode = (clone $query)
                ->whereHas('home', function ($home) use ($code) {
                    $home->where('code', $code)->orWhere('id', $code);
                })
                ->latest('id')
                ->first();

            if ($byCode) {
                return $byCode;
            }
        }

        if ($smsLog->created_at) {
            return $query
                ->whereBetween('created_at', [
                    $smsLog->created_at->copy()->subMinutes(10),
                    $smsLog->created_at->copy()->addMinutes(10),
                ])
                ->latest('id')
                ->first();
        }

        return null;
    }

    private static function resolveInboxHome(SmsLog $smsLog, ?Order $order): ?Home
    {
        $related = $smsLog->related;

        if ($related instanceof Home) {
            return $related;
        }

        if ($order && $order->home_id) {
            return $order->relationLoaded('home') ? $order->home : Home::query()->find($order->home_id);
        }

        return null;
    }

    /**
     * @param  array<string, string>  $parameters
     */
    private static function resolveCalendarUrl(array $parameters, ?Order $order, ?Home $home): string
    {
        $path = trim((string) ($parameters['CALENDAR-LINK'] ?? $parameters['CALENDAR_LINK'] ?? ''));

        if ($path !== '') {
            return str_starts_with($path, 'http') ? $path : url($path);
        }

        $homeId = $home->id ?? $order->home_id ?? null;

        if (! $homeId) {
            return '';
        }

        return url(ltrim(route('host.calendar.short', $homeId, false), '/'));
    }

    /**
     * @param  array<string, string>  $parameters
     */
    public static function replacePlaceholders(string $text, array $parameters): string
    {
        foreach ($parameters as $key => $value) {
            $text = str_replace(['%'.$key.'%', '#'.$key.'#'], $value, $text);
        }

        $text = (string) preg_replace('/%[A-Z0-9_-]*LINK[A-Z0-9_-]*%/', '', $text);

        return trim((string) preg_replace('/%[A-Z0-9_-]+%/', '—', $text));
    }

    private function computeUnreadCount(User $user): int
    {
        $readNewsletterIds = InboxRead::query()
            ->where('user_id', $user->id)
            ->where('readable_type', (new Newsletter())->getMorphClass())
            ->pluck('readable_id');

        $newsletterCount = $this->newsletterQuery($user)
            ->whereNotIn('id', $readNewsletterIds)
            ->count();

        $readSmsIds = InboxRead::query()
            ->where('user_id', $user->id)
            ->where('readable_type', (new SmsLog())->getMorphClass())
            ->pluck('readable_id');

        $smsCount = $this->userSmsQuery($user)
            ->whereNotIn('id', $readSmsIds)
            ->count();

        return $newsletterCount + $smsCount;
    }

    private function newsletterQuery(User $user): Builder
    {
        return Newsletter::query()
            ->visibleTo($user)
            ->where('created_at', '>=', $user->created_at ?? now()->subYears(100));
    }

    private function userSmsQuery(User $user): Builder
    {
        $hiddenPatternIds = SmsTemplates::hiddenInboxPatternIds();
        $hiddenTitles = SmsTemplates::hiddenInboxTitles();

        return SmsLog::query()
            ->with('related')
            ->where(function (Builder $query) use ($user) {
                $query->where('user_id', $user->id);

                $mobile = trim((string) $user->mobile);
                if ($mobile !== '') {
                    $query->orWhere('mobile', $mobile);
                }
            })
            ->whereIn('status', [SmsLog::STATUS_SENT, SmsLog::STATUS_SKIPPED])
            ->when($hiddenPatternIds !== [], function (Builder $query) use ($hiddenPatternIds) {
                $query->whereNotIn('pattern_id', $hiddenPatternIds);
            })
            ->when($hiddenTitles !== [], function (Builder $query) use ($hiddenTitles) {
                $query->where(function (Builder $inner) use ($hiddenTitles) {
                    $inner->whereNull('pattern_title')
                        ->orWhereNotIn('pattern_title', $hiddenTitles);
                });
            });
    }

    /**
     * @param  array<int, string>  $readKeys
     */
    private function fromNewsletter(Newsletter $newsletter, array $readKeys): UserInboxMessage
    {
        $isUnread = ! in_array((new Newsletter())->getMorphClass().':'.$newsletter->id, $readKeys, true);

        return new UserInboxMessage(
            UserInboxMessage::TYPE_NEWSLETTER,
            (int) $newsletter->id,
            (string) $newsletter->title,
            (string) $newsletter->body,
            $newsletter->created_at,
            $isUnread,
            __('title.inbox_kind_newsletter'),
            true
        );
    }

    /**
     * @param  array<int, string>  $readKeys
     */
    private function fromSms(SmsLog $smsLog, array $readKeys): UserInboxMessage
    {
        $isUnread = ! in_array((new SmsLog())->getMorphClass().':'.$smsLog->id, $readKeys, true);
        $title = trim((string) ($smsLog->pattern_title ?: __('title.inbox_kind_sms')));

        return new UserInboxMessage(
            UserInboxMessage::TYPE_SMS,
            (int) $smsLog->id,
            $title,
            self::renderSmsBody($smsLog),
            $smsLog->created_at,
            $isUnread,
            __('title.inbox_kind_sms'),
            false
        );
    }

    private function readableType(string $type): string
    {
        if ($type === self::TYPE_NEWSLETTER) {
            return (new Newsletter())->getMorphClass();
        }

        if ($type === self::TYPE_SMS) {
            return (new SmsLog())->getMorphClass();
        }

        abort(404);
    }
}
