<?php

namespace App\Models;

use App\Classes\Date;
use App\Classes\Traits\Favoritable;
use App\Classes\Traits\HasComment;
use App\Classes\Traits\PersianDate;
use App\Services\HomePhotoWebpEncoder;
use App\Services\HomeSmartSearchService;
use App\Support\HomeSlug;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Morilog\Jalali\Jalalian;

class Home extends Model
{
    use HasFactory, HasComment, PersianDate, Favoritable;

    protected $guarded = [];

    protected $with = [
        'images',
        'province',
        'city'
    ];

    protected $casts = [
        'is_draft' => 'bool',
        'is_host_active' => 'bool',
        'draft_step' => 'integer',
        'fast_reserve_start_at' => 'date',
        'fast_reserve_end_at' => 'date'
    ];

    /** @var array<int, string> */
    protected $appends = [
        'cover_path',
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            if (!$model->code) {
                $model->code = self::generateUniqueCode();
            }
        });
    }

    public static function generateUniqueCode(): string
    {
        do {
            $code = rand(1000, 999999);
        } while (self::query()->where('code', $code)->exists());

        return $code;
    }

    # region Const
    const EASY = 'easy';
    const BALANCED = 'balanced';
    const STRICT = 'strict';
    const REJECT_POLICIES = [
        self::EASY => [
            'value' => self::EASY,
            'title' => 'سهل گیرانه',
        ],
        self::BALANCED => [
            'value' => self::BALANCED,
            'title' => 'متعادل',
        ],
        self::STRICT => [
            'value' => self::STRICT,
            'title' => 'سخت گیرانه',
        ],
    ];

    const FILE_PATH = 'files/homes/images/';
    const DOCUMENT_PATH = 'files/homes/documents/';
    /** طول بلندترین ضلع کاور (پیکسل) */
    const IMAGE_MAX_LONG_EDGE = 2400;
    /** کیفیت WebP کاور (۰–۱۰۰) */
    const IMAGE_WEBP_QUALITY = 86;

    /** گالری: ابعاد کوچک‌تر برای حجم کمتر و بارگذاری سریع‌تر برای کاربر نهایی */
    const IMAGE_GALLERY_MAX_LONG_EDGE = 1920;
    const IMAGE_GALLERY_WEBP_QUALITY = 78;

    /** حداکثر حجم هر فایل ورودی قبل از فشرده‌سازی (کیلوبایت؛ قانون Laravel نوعی «max» روی فایل) */
    const MAX_IMAGE_SIZE = 1024 * 30;
    const MAX_VIDEO_SIZE = 1024 * 20;

    const ACCEPTED = 'accepted';
    const PENDING = 'pending';
    const REJECTED = 'rejected';
    const STATUSES = [
        self::PENDING => [
            'value' => self::PENDING,
            'fa_text' => 'در حال بررسی',
            'color' => 'warning'
        ],
        self::ACCEPTED => [
            'value' => self::ACCEPTED,
            'fa_text' => 'تایید شده',
            'color' => 'success'
        ],
        self::REJECTED => [
            'value' => self::REJECTED,
            'fa_text' => 'رد شده',
            'color' => 'danger'
        ],
    ];

    const HOST_DEACTIVATION_UNAVAILABLE = 'unavailable';
    const HOST_DEACTIVATION_MAINTENANCE = 'maintenance';
    const HOST_DEACTIVATION_OTHER = 'other';
    const HOST_DEACTIVATION_REASONS = [
        self::HOST_DEACTIVATION_UNAVAILABLE => [
            'value' => self::HOST_DEACTIVATION_UNAVAILABLE,
            'label' => 'اقامتگاه در دسترس نیست',
        ],
        self::HOST_DEACTIVATION_MAINTENANCE => [
            'value' => self::HOST_DEACTIVATION_MAINTENANCE,
            'label' => 'اقامتگاه تعمیرات دارد',
        ],
        self::HOST_DEACTIVATION_OTHER => [
            'value' => self::HOST_DEACTIVATION_OTHER,
            'label' => 'سایر موارد',
        ],
    ];

    const DARBAST = 'darbast';
    const OTAGH_KHOSOSI = 'otagh_khososi';
    const NIME_DARBAST = 'nime_darbast';
    const OTAGH_MOSHTAREK = 'otagh_moshtarek';
    const ATMOSPHERES = [
        self::DARBAST => [
            'value' => self::DARBAST,
            'fa_text' => 'دربست'
        ],
        self::OTAGH_KHOSOSI => [
            'value' => self::OTAGH_KHOSOSI,
            'fa_text' => 'اتاق خصوصی'
        ],
        self::NIME_DARBAST => [
            'value' => self::NIME_DARBAST,
            'fa_text' => 'نیمه دربست'
        ],
        self::OTAGH_MOSHTAREK => [
            'value' => self::OTAGH_MOSHTAREK,
            'fa_text' => 'اتاق مشترک'
        ]
    ];

    const VILAIY = 'vilaiy';
    const APARTEMAN = 'aparteman';
    const SWIIT = 'swiit';
    const KHANE_ROOSTANIY = 'khane_roostaniy';
    const KOLBEH = 'kolbeh';
    const EGHAMATGAH_BOOM_GARDY = 'eghamatgah_boom_gardy';
    const HOTEL_APARTEMAN = 'hotel_aparteman';
    const MEHMAN_KHANE = 'mahman_khane';
    const CHADOR_KHEIME = 'chador_kheime';
    const PANSION = 'pansion';
    const HASTEL = 'hastel';
    const KARVAN_SARA = 'karvani_sara';
    const BOTIK_HOTEL = 'botik_hotel';
    const GHAIEGH = 'ghaiegh';
    const TYPES = [
        self::VILAIY => [
            'value' => self::VILAIY,
            'fa_text' => 'ویلایی'
        ],
        self::APARTEMAN => [
            'value' => self::APARTEMAN,
            'fa_text' => 'آپارتمان'
        ],
        self::SWIIT => [
            'value' => self::SWIIT,
            'fa_text' => 'سوییت'
        ],
        self::KHANE_ROOSTANIY => [
            'value' => self::KHANE_ROOSTANIY,
            'fa_text' => 'خانه روستایی'
        ],
        self::KOLBEH => [
            'value' => self::KOLBEH,
            'fa_text' => 'کلبه'
        ],
        self::EGHAMATGAH_BOOM_GARDY => [
            'value' => self::EGHAMATGAH_BOOM_GARDY,
            'fa_text' => 'اقامتگاه بوم گردی'
        ],
        self::HOTEL_APARTEMAN => [
            'value' => self::HOTEL_APARTEMAN,
            'fa_text' => 'هتل آپارتمان'
        ],
        self::MEHMAN_KHANE => [
            'value' => self::MEHMAN_KHANE,
            'fa_text' => 'مهمان خانه'
        ],
        self::CHADOR_KHEIME => [
            'value' => self::CHADOR_KHEIME,
            'fa_text' => 'چادر/خیمه'
        ],
        self::PANSION => [
            'value' => self::PANSION,
            'fa_text' => 'پانسیون'
        ],
        self::HASTEL => [
            'value' => self::HASTEL,
            'fa_text' => 'هاستل'
        ],
        self::KARVAN_SARA => [
            'value' => self::KARVAN_SARA,
            'fa_text' => 'کاروانسرا'
        ],
        self::BOTIK_HOTEL => [
            'value' => self::BOTIK_HOTEL,
            'fa_text' => 'بوتیک هتل'
        ],
        self::GHAIEGH => [
            'value' => self::GHAIEGH,
            'fa_text' => 'قایق'
        ]
    ];

    const SAHELI = 'saheli';
    const JANGALI = 'jangali';
    const IEILAGHI = 'ieilaghi';
    const BIABANI = 'biabani';
    const SHAHRI = 'shahri';
    const HOME_SHAHR = 'home_shahr';
    const ROOSTAIY = 'roostaiy';
    const AREAS = [
        self::SAHELI => [
            'value' => self::SAHELI,
            'fa_text' => 'ساحلی'
        ],
        self::JANGALI => [
            'value' => self::JANGALI,
            'fa_text' => 'جنگلی'
        ],
        self::IEILAGHI => [
            'value' => self::IEILAGHI,
            'fa_text' => 'ییلاقی'
        ],
        self::BIABANI => [
            'value' => self::BIABANI,
            'fa_text' => 'بیابانی'
        ],
        self::SHAHRI => [
            'value' => self::SHAHRI,
            'fa_text' => 'شهری'
        ],
        self::HOME_SHAHR => [
            'value' => self::HOME_SHAHR,
            'fa_text' => 'حومه شهر'
        ],
        self::ROOSTAIY => [
            'value' => self::ROOSTAIY,
            'fa_text' => 'روستایی'
        ],
    ];


    const DAILY_OFF_AMOUNTS = [
        0 => [
            'text'  => 'بدون تخفیف',
            'value' => 0
        ],
        5 => [
            'text'  => '5 درصد',
            'value' => 5
        ],
        10 => [
            'text'  => '10 درصد',
            'value' => 10
        ],
        15 => [
            'text'  => '15 درصد',
            'value' => 15
        ],
        20 => [
            'text'  => '20 درصد',
            'value' => 20
        ],
        25 => [
            'text'  => '25 درصد',
            'value' => 25
        ],
        30 => [
            'text'  => '30 درصد',
            'value' => 30
        ],
        35 => [
            'text'  => '35 درصد',
            'value' => 35
        ],
        40 => [
            'text'  => '40 درصد',
            'value' => 40
        ],
        45 => [
            'text'  => '45 درصد',
            'value' => 45
        ],
        50 => [
            'text'  => '50 درصد',
            'value' => 50
        ],
    ];
    # endregion

    # region Methods
    public function getAdminRoute(): string
    {
        return route('admin.homes.index', ['id' => $this->id]);
    }

    public static function normalizeSlug(?string $slug): string
    {
        return HomeSlug::normalize($slug);
    }

    public function suggestSlug(): string
    {
        return HomeSlug::suggestFor($this);
    }

    public function slugRouteSegment(): string
    {
        return HomeSlug::routeSegment($this);
    }

    public function getRouteKey(): string
    {
        return $this->slugRouteSegment();
    }

    public function getPublicShowUrlAttribute(): string
    {
        return route('main.homes.show', $this);
    }

    public function getPriceFormatted($today = false, $tomorrow = false)
    {
        $price = number_format($this->week_price);
        if ($today){
            $price = number_format($this->getPrice(now()->startOfDay()));
        }
        if ($tomorrow){
            $price = number_format($this->getPrice(now()->addDay()->startOfDay()));
        }

        return $price;
    }

    public function price($today = false, $tomorrow = false)
    {
        return $this->getPriceFormatted($today, $tomorrow);
    }

    public function hasLongStayDiscount(): bool
    {
        return (int) $this->daily_off > 0 && (int) $this->daily_off_amount > 0;
    }

    public function longStayDiscountLabel(): ?string
    {
        if (! $this->hasLongStayDiscount()) {
            return null;
        }

        return persianNumber((int) $this->daily_off_amount, 0).'٪ تخفیف از '.persianNumber((int) $this->daily_off, 0).' شب';
    }

    public function minBaseNightlyPrice(): int
    {
        return min(
            (int) $this->week_price,
            (int) $this->wed_price,
            (int) $this->thu_price,
            (int) $this->fri_price,
        );
    }

    public function minNightlyPriceFormatted(): string
    {
        return persianNumber($this->minBaseNightlyPrice());
    }

    public static function defaultCover(): string
    {
        return 'https://via.placeholder.com/2200';
    }

    protected function getScoreColumn(): ?string
    {
        return 'score';
    }

    protected function getCountCommentColumn(): ?string
    {
        return 'count_comments';
    }

    public function getImagePath(): string
    {
        return self::FILE_PATH.$this->id.'/';
    }

    public function getDocumentPath(): string
    {
        return self::DOCUMENT_PATH.$this->id.'/';
    }

    public function getCommission(): int
    {
        switch ($this->reject_policy)
        {
            case self::EASY:
                return setting('commission:easy', 0);

            case self::BALANCED:
                return setting('commission:balanced', 0);

            case self::STRICT:
                return setting('commission:strict', 0);

            default:
                return 0;
        }
    }

    public static function getRejectPolicyDescription(string $policy): ?string
    {
        return setting('reject-policy:'.$policy);
    }

    public function addImage(UploadedFile $file): HomeImage
    {
        $originalName = $file->getClientOriginalName();

        $stored = app(HomePhotoWebpEncoder::class)->storeOptimizedWebp($file, self::FILE_PATH.$this->id, [
            'max_edge' => self::IMAGE_GALLERY_MAX_LONG_EDGE,
            'quality' => self::IMAGE_GALLERY_WEBP_QUALITY,
        ]);

        return $this->images()->create([
            'original_name' => $originalName,
            'name' => $stored['name'],
            'size' => $stored['size'],
            'type' => $stored['type'],
        ]);
    }

    public function addDocument(UploadedFile $file): HomeDocument
    {
        $originalName = $file->getClientOriginalName();
        $ext = strtolower((string) $file->getClientOriginalExtension());
        $mime = strtolower((string) $file->getMimeType());
        $isPdf = $ext === 'pdf' || $mime === 'application/pdf';

        if (! $isPdf) {
            try {
                $stored = app(HomePhotoWebpEncoder::class)->storeOptimizedJpeg(
                    $file,
                    self::DOCUMENT_PATH.$this->id,
                    ['max_edge' => 1200, 'quality' => 80]
                );

                return $this->storeDocumentRecord($stored, $originalName);
            } catch (\Throwable $e) {
                // در صورت خطا (مثلاً HEIC بدون تبدیل سمت کلاینت) فایل خام ذخیره می‌شود.
            }
        }

        $storedPath = $file->store(self::DOCUMENT_PATH.$this->id.'/', 'public-folder');
        $name = basename($storedPath);

        return $this->storeDocumentRecord([
            'name' => $name,
            'size' => (int) ($file->getSize() ?: 0),
            'type' => $mime ?: $file->getClientMimeType(),
        ], $originalName);
    }

    /**
     * @param  array{name: string, size?: int, type?: string|null}  $stored
     */
    protected function storeDocumentRecord(array $stored, string $originalName): HomeDocument
    {
        $document = $this->documents()->firstOrCreate(
            ['name' => $stored['name']],
            [
                'original_name' => $originalName,
                'size' => (int) ($stored['size'] ?? 0),
                'mime' => $stored['type'] ?? null,
            ]
        );

        if (! $document->wasRecentlyCreated) {
            $document->update([
                'original_name' => $originalName,
                'size' => (int) ($stored['size'] ?? $document->size),
                'mime' => $stored['type'] ?? $document->mime,
            ]);
        }

        $this->update(['document' => $stored['name']]);

        return $document;
    }

    public function syncDocumentsFromDisk(): void
    {
        if (! $this->id || ! \Illuminate\Support\Facades\Schema::hasTable('home_documents')) {
            return;
        }

        $dir = trim($this->getDocumentPath(), '/');

        if (! Storage::disk('public-folder')->exists($dir)) {
            return;
        }

        foreach (Storage::disk('public-folder')->files($dir) as $relativePath) {
            $name = basename($relativePath);

            if ($name === '' || $name === '.' || $name === '..') {
                continue;
            }

            $this->documents()->firstOrCreate(
                ['name' => $name],
                [
                    'original_name' => $name,
                    'size' => (int) Storage::disk('public-folder')->size($relativePath),
                    'mime' => null,
                ]
            );
        }

        $latest = $this->documents()->orderByDesc('id')->first();

        if ($latest && $this->document !== $latest->name) {
            $this->update(['document' => $latest->name]);
        }
    }

    public function deleteDocument(): void
    {
        if ($this->document) {
            Storage::disk('public-folder')->delete($this->getDocumentPath().$this->document);
        }
    }

    public function deleteCover(): Home
    {
        if ($this->cover){
            Storage::delete(self::FILE_PATH.$this->id.'/'.$this->cover);
        }
        $this->update(['cover' => null]);

        return $this;
    }

    public function updateCover(UploadedFile $file): Home
    {
        $this->deleteCover();

        $stored = app(HomePhotoWebpEncoder::class)->storeOptimizedWebp($file, self::FILE_PATH.$this->id);
        $this->update(['cover' => $stored['name']]);

        return $this;
    }

    public function deleteVideo(): Home
    {
        if ($this->video){
            Storage::delete(self::FILE_PATH.$this->id.'/'.$this->video);
        }
        $this->update(['video' => null]);

        return $this;
    }

    public function updateVideo(UploadedFile $file): Home
    {
        $this->deleteVideo();

        $file = basename($file->store(self::FILE_PATH.$this->id.'/'));
        $this->update(['video' => $file]);

        return $this;
    }

    public function updateVariable(int $variable_id, $value): HomeVariable
    {
        $variable = Variable::query()->findOrFail($variable_id);
        if ($variable->input_type !== Variable::TEXT){
            $data = [
                'option_id' => $value
            ];
        }
        else {
            $data = [
                'value' => $value
            ];
        }

        return $this->variables()->updateOrCreate(['variable_id' => $variable_id], $data);
    }

    public function atmosphere($index = 'fa_text'): string
    {
        return self::ATMOSPHERES[$this->atmosphere][$index];
    }

    /**
     * برچسب فارسی نوع اقامتگاه (ستون دیتابیس `type` با متد هم‌نام تداخل دارد؛ از این متد استفاده کنید).
     */
    public function typeLabel(string $index = 'fa_text'): string
    {
        $value = $this->attributes['type'] ?? null;

        if ($value === null || $value === '' || ! array_key_exists($value, self::TYPES)) {
            return '';
        }

        return self::TYPES[$value][$index];
    }

    public function area($index = 'fa_text'): string
    {
        return self::AREAS[$this->area][$index];
    }

    public function status($index = 'fa_text'): string
    {
        return self::STATUSES[$this->status][$index];
    }

    public function rejectPolicy($index = 'title'): string
    {
        if ($index === 'description')
        {
            return self::getRejectPolicyDescription($this->reject_policy);
        }

        return self::REJECT_POLICIES[$this->reject_policy][$index];
    }

    public static function getRejectPolicies(): array
    {
        $reject_policies = self::REJECT_POLICIES;

        return array_map(function ($policy) {
            $policy['description'] = self::getRejectPolicyDescription($policy['value']);

            return $policy;

        }, $reject_policies);
    }

    public function calcPrice(Carbon $start_date, Carbon $end_date, int $extra_guest = 0): int
    {
        $period = CarbonPeriod::between($start_date, $end_date);
        $price = $extra_guest * $this->price_per_surplus * $period->count();

        foreach ($period as $date){
            $price += $this->getPrice($date);
        }

        if ($this->daily_off !== 0 && $this->daily_off_amount !== 0 && $period->count() > $this->daily_off){
            $price -= (($this->daily_off_amount * $price) / 100);
        }

        return $price;
    }

    public function getPrice(Carbon $date, bool $is_original_price = false): int
    {
        $custom_prices = $this->custom_prices->pluck('price', 'date')->toArray();

        if (!$is_original_price && isset($custom_prices[$date->format('Y-m-d H:i:s')])){
            $price = $custom_prices[$date->format('Y-m-d H:i:s')];
        }
        elseif ($date->isThursday() || Date::isHoliday($date->clone()->addDay())){
            $price = $this->thu_price;
        }
        elseif ($date->isFriday() || Date::isHoliday($date)){
            $price = $this->fri_price;
        }
        elseif ($date->isWednesday()){
            $price = $this->wed_price;
        }
        else {
            $price = $this->week_price;
        }
        if (! $is_original_price && $this->appliesLastMinuteOff($date)) {
            $price = $price - ($this->off * $price / 100);
        }

        return $price;
    }

    /**
     * تخفیف لحظه‌آخری: فقط برای «امروز» و وقتی آن روز در تقویم قابل رزرو باشد (رزرو/بسته نباشد).
     */
    public function appliesLastMinuteOff(Carbon $date): bool
    {
        if ((int) $this->off === 0) {
            return false;
        }

        if (! $date->copy()->startOfDay()->equalTo(now()->startOfDay())) {
            return false;
        }

        return ! $this->disable_dates->contains($date->format('Y/m/d'));
    }

    public static function getCreateHomeHelps(): Collection
    {
        return collect([
            1 => setting('new-home:help-page-1'),
            2 => setting('new-home:help-page-2'),
            3 => setting('new-home:help-page-3'),
            4 => setting('new-home:help-page-4'),
            5 => setting('new-home:help-page-5'),
            6 => setting('new-home:help-page-6'),
            7 => setting('new-home:help-page-7'),
            8 => setting('new-home:help-page-8'),
            9 => setting('new-home:help-page-9'),
            10 => setting('new-home:help-page-10'),
            11 => setting('new-home:help-page-11'),
            12 => setting('new-home:help-page-12'),
            13 => setting('new-home:help-page-13'),
            14 => setting('new-home:help-page-14'),
            15 => setting('new-home:help-page-15'),
        ]);
    }

    public function hasFastReserve(Carbon $start, Carbon $end): bool
    {
        $result = false;
        if ($this->fast_reserve_start_at &&
            $this->fast_reserve_end_at &&
            ($start->gte($this->fast_reserve_start_at) && $start->lte($this->fast_reserve_end_at)) &&
            ($end->gte($this->fast_reserve_start_at) && $end->lte($this->fast_reserve_end_at))){

            $result = true;
        }

        return $result;
    }
    # endregion

    # region Scopes
    public function scopeActive($query)
    {
        return $query
            ->where('status', self::ACCEPTED)
            ->where('is_draft', false)
            ->where('is_host_active', true);
    }

    public function scopeHostInactive($query)
    {
        return $query->where('is_host_active', false);
    }

    public function isHostActive(): bool
    {
        return (bool) ($this->is_host_active ?? true);
    }

    public function hostDeactivationReasonLabel(): ?string
    {
        if (! $this->host_deactivation_reason) {
            return null;
        }

        return self::HOST_DEACTIVATION_REASONS[$this->host_deactivation_reason]['label'] ?? null;
    }

    /**
     * اقامتگاه‌هایی که تخفیف لحظه‌آخری (فیلد off) برای امروز فعال و قابل رزرو است.
     */
    public function scopeLastMinuteOffAvailable(Builder $query, ?Carbon $date = null): Builder
    {
        $date = ($date ?? now())->copy()->startOfDay();

        return $query
            ->where('homes.off', '>', 0)
            ->whereDoesntHave('orders', function (Builder $query) use ($date) {
                $query->where('start_at', '<=', $date)
                    ->where('end_at', '>=', $date)
                    ->whereIn('status', [Order::AWAITING_PAYMENT, Order::WAITING_FOR_RENTER, Order::IN_RENT]);
            })
            ->whereDoesntHave('custom_dates', function (Builder $query) use ($date) {
                $query->where('date', $date)
                    ->where('price', 0);
            })
            ->orderByDesc('homes.off');
    }

    /**
     * تخفیف از طریق قیمت سفارشی تقویم (پایین‌تر از نرخ پایه امروز).
     */
    public function scopeTodayOff(Builder $query): Builder
    {
        $date = Jalalian::now()->toCarbon()->startOfDay();
        $column = 'week_price';

        if ($date->isWednesday())
        {
            $column = 'wed_price';
        }
        if ($date->isThursday())
        {
            $column = 'thu_price';
        }
        if ($date->isFriday())
        {
            $column = 'fri_price';
        }

        return $query->join('home_custom_dates', function ($join) use ($date, $column) {
            $join->on('homes.id', '=', 'home_custom_dates.home_id')
                ->where('home_custom_dates.date', '=', $date)
                ->where('home_custom_dates.price', '!=', 0)
                ->whereColumn('home_custom_dates.price', '<', "homes.$column");
        })
            ->orderByRaw("homes.$column - home_custom_dates.price DESC");
    }

    public function scopeOpenTomorrow(Builder $query): Builder
    {
        $tomorrow = now()->addDay()->startOfDay();

        return $query
            ->whereDoesntHave('orders', function (Builder $query) use ($tomorrow) {
                $query->where('start_at', '<=', $tomorrow)
                    ->where('end_at', '>=', $tomorrow)
                    ->whereIn('status', [Order::AWAITING_PAYMENT, Order::WAITING_FOR_RENTER, Order::IN_RENT, Order::DONE]);
            })
            ->whereDoesntHave('custom_dates', function (Builder $query) use ($tomorrow) {
                $query->where('date', $tomorrow)
                    ->where('price', 0);
            });
    }

    public function scopeSearch(Builder $query)
    {
        if (request()->filled('id')){
            $query->where('id', request('id'));
        }
        $searchTerms = request('q', []);
        if (! is_array($searchTerms)) {
            $searchTerms = $searchTerms !== null && $searchTerms !== '' ? [(string) $searchTerms] : [];
        }
        $searchTerms = array_values(array_filter(array_map(
            fn ($t) => trim((string) $t),
            $searchTerms
        ), fn ($t) => $t !== ''));

        if ($searchTerms === []) {
            $legacy = request()->filled('name')
                ? trim((string) request('name'))
                : (request()->filled('search') ? trim((string) request('search')) : '');

            if ($legacy !== '') {
                app(HomeSmartSearchService::class)->applySearchTerm($query, $legacy);
            }
        } else {
            app(HomeSmartSearchService::class)->applySearchTerms($query, $searchTerms);
        }

        $features = request('features', []);
        if (is_array($features) && $features !== []) {
            app(HomeSmartSearchService::class)->applyFeatureSlugs($query, $features);
        }

        if (request()->filled('guest_count')) {
            $query->whereRaw('(main_guest + COALESCE(extra_guest, 0)) >= ?', [(int) request('guest_count')]);
        }

        if (request()->filled('min_price')) {
            $query->where('week_price', '>=', (int) request('min_price'));
        }

        if (request()->filled('max_price')) {
            $query->where('week_price', '<=', (int) request('max_price'));
        }

        if (request()->filled('type')) {
            $typeMap = [
                'villa' => self::VILAIY,
                'apartment' => self::APARTEMAN,
                'house' => self::KHANE_ROOSTANIY,
            ];
            $type = $typeMap[request('type')] ?? request('type');
            $query->where('type', $type);
        }
        if (request()->filled('fast_reserve')){
            $query->whereNotNull('fast_reserve_start_at')
                ->whereNotNull('fast_reserve_end_at');
        }
        if (request()->filled('atmospheres')){
            $query->whereIn('atmosphere', request('atmospheres'));
        }
        if (request()->filled('types')){
            $query->whereIn('type', request('types'));
        }
        if (request()->filled('areas')){
            $query->whereIn('area', request('areas'));
        }
        if (request()->filled('bed_count')){
            $query->whereHas('sleepPlaces', function ($query){
                $query->select(DB::raw('SUM(single_bed + double_bed) as count_bed'))
                    ->havingRaw('count_bed >= '.request('bed_count'));
            });
        }
        if (request()->filled('bedroom_count')){
            $query->whereHas('sleepPlaces', function ($query){
                $query->where('is_share', false);
            }, '>=', request('bedroom_count'));
        }
        if (request()->filled('guest')){
            $query->whereRaw('(main_guest + extra_guest) >= '.request('guest'));
        }
        if (request()->filled('start_at')){
            $date = Jalalian::fromFormat('Y/m/d', request('start_at'))->toCarbon();

            $query->whereDoesntHave('orders', function (Builder $builder) use ($date){
                $builder->whereIn('status', [Order::AWAITING_PAYMENT, Order::WAITING_FOR_RENTER, Order::IN_RENT])
                    ->where('start_at', '<=', $date)
                    ->where('end_at', '>=', $date);
            })
                ->whereDoesntHave('custom_dates', function (Builder $custom_dates) use ($date){
                    $custom_dates->where('price', 0)
                        ->where('date', $date);
                });
        }
        if (request()->filled('end_at')){
            $date = Jalalian::fromFormat('Y/m/d', request('end_at'))->toCarbon();

            $query->whereDoesntHave('orders', function (Builder $builder) use ($date){
                $builder->whereIn('status', [Order::AWAITING_PAYMENT, Order::WAITING_FOR_RENTER, Order::IN_RENT])
                    ->where('start_at', '<=', $date)
                    ->where('end_at', '>=', $date);
            })
                ->whereDoesntHave('custom_dates', function (Builder $custom_dates) use ($date){
                    $custom_dates->where('price', 0)
                        ->where('date', $date);
                });
        }
        if (request()->filled('start_at') && request()->filled('end_at')){
            $start = Jalalian::fromFormat('Y/m/d', request('start_at'))->toCarbon();
            $end = Jalalian::fromFormat('Y/m/d', request('end_at'))->toCarbon();

            $query->whereDoesntHave('custom_dates', function (Builder $custom_dates) use ($start, $end){
                $custom_dates->where('price', 0)
                    ->whereBetween('date', [$start, $end]);
            });
        }
        if (request()->filled('options') && is_array(request('options'))){
            $query->whereHas('options', function ($options){
                $options->whereIn('option_id', request('options'));
            });
        }
        if (request()->filled('status')){
            $query->where('status', request('status'));
        }
        if (request()->filled('user')){
            $query->where('user_id', request('user'));
        }
        if (request()->filled('province') && request()->filled('city')) {
            // استان + شهر: همه اقامتگاه‌های استان، اول شهر انتخاب‌شده
            $query->where('province_id', request('province'));
            $query->orderByRaw('CASE WHEN city_id = ? THEN 0 ELSE 1 END', [(int) request('city')]);
        } elseif (request()->filled('province')) {
            $query->where('province_id', request('province'));
        } elseif (request()->filled('city')) {
            $query->where('city_id', request('city'));
        }
        if (request()->filled('price_range')){
            $range = explode(';', request('price_range'));
            if (is_array($range) && count($range) === 2){
                $query->whereBetween('week_price', $range);
            }
        }
        if (request()->filled('variables')){
            foreach (request('variables') as $variable_id => $option){
                if ($option){
                    $query->whereHas('variables', function ($query) use ($variable_id, $option){
                        $query->where('variable_id', $variable_id)
                            ->where(function ($query) use ($option){
                                $query->where('option_id', $option)
                                    ->orWhere('value', $option);
                            });
                    });
                }
            }
        }
        if (request()->filled('min_area')){
            $query->where('infrastructure_meter', '>', request('min_area'));
        }
        if (request()->filled('max_area')){
            $query->where('infrastructure_meter', '<', request('max_area'));
        }
        if (request()->filled('filter')){
            if (request('filter') === 'open_now'){
                $query->whereDoesntHave('orders', function (Builder $query){
                    $date = now()->startOfDay();
                    $query->where('start_at', '<=', $date)->where('end_at', '>=', $date);

                });
            }
            if (request('filter') === 'open_tomorrow'){
                $query->whereDoesntHave('orders', function (Builder $query){
                    $date = now()->addDay()->startOfDay();
                    $query->where('start_at', '<=', $date)->where('end_at', '>=', $date);

                });
            }
            if (request('filter') === 'off'){
                $query->lastMinuteOffAvailable();
            }
        }

        if (request()->filled('sort')){
            if (request('sort') === 'expensive'){
                $query->orderByDesc('week_price');
            }
            if (request('sort') === 'cheap'){
                $query->orderBy('week_price');
            }
            if (request('sort') === 'popular'){
                $query->orderByDesc('fake_score');
            }
        }

        return $query;
    }
    public function hasGuestReviews(): bool
    {
        return (int) $this->count_comments > 0 && (float) $this->score > 0;
    }

    public function guestRatingScore(): ?float
    {
        if (! $this->hasGuestReviews()) {
            return null;
        }

        return round((float) $this->score, 1);
    }

    public function guestRatingScoreForDisplay(): ?string
    {
        $score = $this->guestRatingScore();

        if ($score === null) {
            return null;
        }

        $decimals = fmod($score, 1.0) ? 1 : 0;

        return persianNumber($score, $decimals);
    }

    public function guestRatingStars(): int
    {
        if (! $this->hasGuestReviews()) {
            return 0;
        }

        return max(1, min(5, (int) round((float) $this->score)));
    }

    public function guestRatingPayload(): array
    {
        $hasReviews = $this->hasGuestReviews();

        return [
            'guest_score' => $this->guestRatingScore(),
            'guest_score_display' => $this->guestRatingScoreForDisplay(),
            'guest_stars' => $this->guestRatingStars(),
            'has_guest_reviews' => $hasReviews,
            'count_comments' => (int) $this->count_comments,
            'score_label' => $hasReviews ? match (true) {
                (float) $this->score >= 5 => 'ممتاز',
                (float) $this->score >= 4 => 'عالی',
                default => null,
            } : null,
        ];
    }

    # endregion

    # region Accessories
    public function getHasFastReserveAttribute(): bool
    {
        return ($this->fast_reserve_start_at && $this->fast_reserve_end_at);
    }

    public function getFastReserveDatesAttribute(): Collection
    {
        if (!$this->fast_reserve_start_at || !$this->fast_reserve_end_at){
            return collect([]);
        }

        return collect(CarbonPeriod::create($this->fast_reserve_start_at, $this->fast_reserve_end_at)->toArray())->map(function (Carbon $date){
            return $date->format('Y/m/d');
        });
    }

    public function getDetailTextAttribute(): string
    {
        $infrastructure_meter = number_format($this->infrastructure_meter);
        $yard_meter = number_format($this->yard_meter);
        return "خانه {$this->typeLabel()} ،زیربنا {$infrastructure_meter} متر، کل متراژ {$yard_meter} متر";
    }

    public function getGuestTextAttribute(): string
    {
        $text = 'ظرفیت '. number_format($this->main_guest) .' نفر';
        if ($this->extra_guest){
            $text .= ' قابل افزایش تا '. number_format($this->main_guest + $this->extra_guest) .' نفر';
        }

        return $text;
    }

    public function getBedroomTextAttribute(): string
    {
        $bedrooms = ($this->sleepPlaces->where('is_share', false));
        $text = ($bedrooms->count() !== 0) ? number_format($bedrooms->count()). ' اتاق خواب': 'بدون اتاق خواب';

        $text .= ' - ';

        $count_beds = $bedrooms->sum('single_bed') + $bedrooms->sum('double_bed');
        $text .= ($count_beds !== 0) ? number_format($count_beds). ' تخت': 'بدون تخت';

        return $text;
    }

    public function getCoversAttribute(): array
    {
        $images = [];

        if ($this->cover){
            $images[] = $this->cover_path;
        }

        foreach ($this->images as $image){
            $images[] = $image->image_path;
        }

        return $images;
    }

    public function getLimitCoversAttribute(): array
    {
        $images = [];

        if ($this->cover){
            $images[] = $this->cover_path;
        }

        foreach ($this->images->take(2) as $image){
            $images[] = $image->image_path;
        }

        return $images;
    }

    public function getCoverPathAttribute(): string
    {
        $cover = $this->attributes['cover'] ?? null;

        // فرم Vue در create، cover را به { image_path } تبدیل می‌کند
        if (is_array($cover)) {
            return (string) ($cover['image_path'] ?? asset('assets/images/placeholder.jpg'));
        }

        if (! $cover) {
            return asset('assets/images/placeholder.jpg');
        }

        if (filter_var($cover, FILTER_VALIDATE_URL)) {
            return $cover;
        }

        if (str_contains($cover, '/')) {
            return asset('storage/' . ltrim($cover, '/'));
        }

        return asset($this->getImagePath() . $cover);
    }

    public function getDocumentPathAttribute(): string
    {
        if (! $this->document) {
            return '';
        }

        return '/'.ltrim($this->getDocumentPath().$this->document, '/');
    }

    public function getVideoPathAttribute(): string
    {
        return asset($this->getImagePath() . $this->video);
    }

    public function getDisableOrderDatesAttribute(): Collection
    {
        $orders = $this->orders()
            ->where('start_at', '>=', now()->startOfDay())
            ->oldest('start_at')
            ->whereIn('status', [Order::AWAITING_PAYMENT, Order::WAITING_FOR_RENTER, Order::IN_RENT])
            ->get(['start_at', 'end_at']);

        $dates = collect([]);
        foreach ($orders as $order){
            $period = CarbonPeriod::create($order->start_at, $order->end_at);
            foreach ($period as $date){

                $dates->push($date->format('Y/m/d'));
            }
        }

        return $dates;
    }

    public function getCustomPricesAttribute(): Collection
    {
        return $this->custom_dates()->where('price', '!=', 0)->get();
    }

    public function getCustomMinNightsMapAttribute(): array
    {
        $map = [];

        foreach ($this->custom_dates as $customDate) {
            $value = max(1, (int) ($customDate->min_nights ?? 1));

            if ($value <= 1) {
                continue;
            }

            try {
                $date = $this->resolveCalendarDayCarbon($customDate->date);
            } catch (\Throwable $e) {
                continue;
            }

            $keys = [
                $date->format('Y-m-d'),
                $date->format('Y/m/d'),
                Jalalian::fromCarbon($date)->format('Y/m/d'),
            ];

            foreach (array_unique($keys) as $key) {
                if (! isset($map[$key]) || $value > $map[$key]) {
                    $map[$key] = $value;
                }
            }
        }

        return $map;
    }

    /**
     * تاریخ ورودی را به روز میلادی معتبر تبدیل می‌کند (رفع ذخیره اشتباه شمسی به‌عنوان میلادی).
     */
    public function resolveCalendarDayCarbon(Carbon|string $date): Carbon
    {
        $date = Carbon::parse($date)->startOfDay();

        // تاریخ میلادی معتبر (خروجی normalizeDate یا Carbon استاندارد)
        if ($date->year >= 1900 && $date->year <= 2100) {
            return $date;
        }

        // سال شمسی که به‌اشتباه به‌صورت میلادی parse شده (مثلاً 1405-03-03)
        if ($date->year >= 1300 && $date->year < 1500) {
            return Jalalian::fromFormat('Y/m/d', $date->format('Y/m/d'))->toCarbon()->startOfDay();
        }

        return $date;
    }

    private function jalaliLabelForStoredDate(Carbon $stored): ?string
    {
        try {
            return Jalalian::fromCarbon($stored)->format('Y/m/d');
        } catch (\Throwable $e) {
            if ($stored->year >= 1300 && $stored->year < 1500) {
                return $stored->format('Y/m/d');
            }

            return null;
        }
    }

    public function findCustomDateByCalendarDay(Carbon|string $date): ?HomeCustomDate
    {
        $target = $this->resolveCalendarDayCarbon($date);
        $targetJalali = Jalalian::fromCarbon($target)->format('Y/m/d');

        $records = $this->relationLoaded('custom_dates')
            ? $this->custom_dates
            : $this->custom_dates()->get();

        foreach ($records as $customDate) {
            $stored = Carbon::parse($customDate->date)->startOfDay();

            if ($stored->isSameDay($target)) {
                return $customDate;
            }

            if ($this->jalaliLabelForStoredDate($stored) === $targetJalali) {
                return $customDate;
            }
        }

        return null;
    }

    public function purgeDuplicateCustomDatesForDay(Carbon $canonical, int $exceptId): void
    {
        $targetJalali = Jalalian::fromCarbon($canonical)->format('Y/m/d');

        $records = $this->relationLoaded('custom_dates')
            ? $this->custom_dates
            : $this->custom_dates()->get();

        foreach ($records as $customDate) {
            if ((int) $customDate->id === $exceptId) {
                continue;
            }

            $stored = Carbon::parse($customDate->date)->startOfDay();
            $isDuplicate = $stored->isSameDay($canonical)
                || $this->jalaliLabelForStoredDate($stored) === $targetJalali;

            if ($isDuplicate) {
                $customDate->delete();
            }
        }
    }

    public function upsertCustomDate(Carbon|string $date, int $price, int $minNights = 1): HomeCustomDate
    {
        $normalizedDate = $this->resolveCalendarDayCarbon($date);
        $minNights = max(1, (int) $minNights);

        $customDate = $this->findCustomDateByCalendarDay($normalizedDate);

        if ($customDate) {
            $customDate->update([
                'date' => $normalizedDate,
                'price' => $price,
                'min_nights' => $minNights,
            ]);

            $this->purgeDuplicateCustomDatesForDay($normalizedDate, (int) $customDate->id);

            return $customDate->fresh();
        }

        $created = $this->custom_dates()->create([
            'date' => $normalizedDate,
            'price' => $price,
            'min_nights' => $minNights,
        ]);

        $this->purgeDuplicateCustomDatesForDay($normalizedDate, (int) $created->id);

        return $created;
    }

    public function getMinNightsForDate(Carbon|string $date): int
    {
        $customDate = $this->findCustomDateByCalendarDay($date);

        if ($customDate && (int) ($customDate->min_nights ?? 0) > 0) {
            return max(1, (int) $customDate->min_nights);
        }

        return 1;
    }

    /**
     * حداقل شب قابل رزرو از یک تاریخ، با توجه به روزهای پر/غیرفعال بعد از آن.
     */
    public function getEffectiveMinNightsForDate(Carbon|string $date): int
    {
        $date = Carbon::parse($date)->startOfDay();
        $configured = $this->getMinNightsForDate($date);

        if ($configured <= 1) {
            return 1;
        }

        $allBlocked = $this->disable_dates
            ->map(fn ($unavailableDate) => Carbon::parse($unavailableDate)->format('Y-m-d'))
            ->flip();

        $maxReserve = Carbon::parse(Order::getMaxReserveDate())->startOfDay();
        $consecutive = 0;
        $cursor = $date->copy();

        while ($consecutive < $configured) {
            if ($cursor->gt($maxReserve)) {
                break;
            }

            if ($allBlocked->has($cursor->format('Y-m-d'))) {
                break;
            }

            $consecutive++;
            $cursor->addDay();
        }

        return max(1, min($configured, $consecutive));
    }

    public function countConsecutiveBookableNightsFromDate(Carbon|string $date, ?int $limit = null): int
    {
        $date = Carbon::parse($date)->startOfDay();
        $limit = $limit ?? 30;

        $allBlocked = $this->disable_dates
            ->map(fn ($unavailableDate) => Carbon::parse($unavailableDate)->format('Y-m-d'))
            ->flip();

        $maxReserve = Carbon::parse(Order::getMaxReserveDate())->startOfDay();
        $consecutive = 0;
        $cursor = $date->copy();

        while ($consecutive < $limit) {
            if ($cursor->gt($maxReserve)) {
                break;
            }

            if ($allBlocked->has($cursor->format('Y-m-d'))) {
                break;
            }

            $consecutive++;
            $cursor->addDay();
        }

        return $consecutive;
    }

    /**
     * بیشترین حداقل شب قابل تنظیم برای یک روز (شامل حالت شب دوم اقامت با ورود روز قبل).
     */
    public function getMaxConfigurableMinNightsForDate(Carbon|string $date, ?int $limit = null): int
    {
        $date = Carbon::parse($date)->startOfDay();
        $limit = $limit ?? 30;
        $cap = 1;

        for ($offset = 0; $offset < $limit; $offset++) {
            $checkIn = $date->copy()->subDays($offset);
            $available = $this->countConsecutiveBookableNightsFromDate($checkIn, $limit);

            if ($available >= $offset + 1) {
                $cap = max($cap, min($limit, $available));
            }
        }

        return $cap;
    }

    /**
     * اگر حداقل شب درخواستی برای مهمان کامل ممکن نباشد، دلیل را برمی‌گرداند.
     */
    public function explainMinNightsLimitation(Carbon|string $date, int $requestedMinNights): ?array
    {
        $date = Carbon::parse($date)->startOfDay();
        $requestedMinNights = max(1, (int) $requestedMinNights);

        if ($requestedMinNights <= 1) {
            return null;
        }

        $bookable = $this->countConsecutiveBookableNightsFromDate($date, $requestedMinNights);

        if ($bookable >= $requestedMinNights) {
            return null;
        }

        if ($this->getMaxConfigurableMinNightsForDate($date, $requestedMinNights) >= $requestedMinNights) {
            return null;
        }

        $reason = $this->findMinNightsBlockReason($date, $requestedMinNights);

        return [
            'date' => $date->format('Y/m/d'),
            'date_label' => persianDate($date)->format('Y/m/d'),
            'configured' => $requestedMinNights,
            'effective' => max(1, $bookable),
            'reason' => $reason['code'],
            'message' => $reason['message'],
        ];
    }

    private function findMinNightsBlockReason(Carbon $checkIn, int $configured): array
    {
        $allBlocked = $this->disable_dates
            ->map(fn ($unavailableDate) => Carbon::parse($unavailableDate)->format('Y-m-d'))
            ->flip();
        $orderBlocked = $this->disable_order_dates
            ->map(fn ($unavailableDate) => Carbon::parse($unavailableDate)->format('Y-m-d'))
            ->flip();
        $hostClosed = $this->disable_custom_dates
            ->map(fn ($unavailableDate) => Carbon::parse($unavailableDate)->format('Y-m-d'))
            ->flip();

        $maxReserve = Carbon::parse(Order::getMaxReserveDate())->startOfDay();
        $cursor = $checkIn->copy();
        $consecutive = 0;

        while ($consecutive < $configured) {
            if ($cursor->gt($maxReserve)) {
                return [
                    'code' => 'max_date',
                    'message' => __('text.min_nights_blocked_max_date', [
                        'date' => persianDate($cursor)->format('Y/m/d'),
                        'configured' => persianNumber($configured),
                    ]),
                ];
            }

            $key = $cursor->format('Y-m-d');
            $isCheckInNight = $cursor->isSameDay($checkIn);

            if ($isCheckInNight) {
                if ($hostClosed->has($key)) {
                    return [
                        'code' => 'host_closed_checkin',
                        'message' => __('text.min_nights_blocked_host_closed_checkin', [
                            'date' => persianDate($cursor)->format('Y/m/d'),
                        ]),
                    ];
                }

                if ($orderBlocked->has($key)) {
                    return [
                        'code' => 'order_checkin',
                        'message' => __('text.min_nights_blocked_order_checkin', [
                            'date' => persianDate($cursor)->format('Y/m/d'),
                        ]),
                    ];
                }
            } elseif ($orderBlocked->has($key) || $hostClosed->has($key)) {
                return [
                    'code' => $orderBlocked->has($key) ? 'order_night' : 'host_closed_night',
                    'message' => __('text.min_nights_blocked_order_night', [
                        'checkin_date' => persianDate($checkIn)->format('Y/m/d'),
                        'blocking_date' => persianDate($cursor)->format('Y/m/d'),
                        'configured' => persianNumber($configured),
                        'effective' => persianNumber(max(1, $consecutive)),
                    ]),
                ];
            }

            $consecutive++;
            $cursor->addDay();
        }

        return [
            'code' => 'unknown',
            'message' => __('text.min_nights_saved_with_limits'),
        ];
    }

    public function getDisableCustomDatesAttribute(): Collection
    {
        $dates  = collect([]);
        foreach ($this->custom_dates()->where('price', 0)->get() as $custom_date){
            $dates->push($custom_date->date->format('Y/m/d'));
        }

        return $dates->sort();
    }

    public function getDisableDatesAttribute(): Collection
    {
        $dates = collect([]);

        foreach ($this->disable_custom_dates as $date){
            $dates->add($date);
        }
        foreach ($this->disable_order_dates as $date){
            $dates->add($date);
        }


        return $dates->unique()->sort();
    }

    public function getAddressTextAttribute(): string
    {
        return "{$this->province->name} ، {$this->city->name} ، $this->address";
    }

    public function getLinkAttribute(): string
    {
        return route('main.homes.visit', $this);
    }

    public function getTotalGuestAttribute(): int
    {
        return $this->main_guest + $this->extra_guest;
    }
    # endregion

    # region Relations
    public function safeties()
    {
        return $this->belongsToMany(Safety::class, 'home_safety')->withPivot('description');
    }

    public function healths()
    {
        return $this->belongsToMany(Health::class, 'home_health');
    }

    public function sleepPlaces()
    {
        return $this->hasMany(HomeSleepPlace::class);
    }

    public function images()
    {
        return $this->hasMany(HomeImage::class)->orderBy('position')->oldest();
    }

    public function documents()
    {
        return $this->hasMany(HomeDocument::class)->orderBy('id');
    }

    public function options()
    {
        return $this->belongsToMany(Option::class);
    }

    public function variables()
    {
        return $this->hasMany(HomeVariable::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function orders()
    {
        return $this->HasMany(Order::class);
    }

    public function custom_dates()
    {
        return $this->HasMany(HomeCustomDate::class);
    }
    # endregion

    # region File Management
    public function addCover(UploadedFile $file): Home
    {
        $this->deleteCover();

        $stored = app(HomePhotoWebpEncoder::class)->storeOptimizedWebp($file, rtrim($this->getImagePath(), '/'));
        $this->update(['cover' => $stored['name']]);

        return $this;
    }

    /**
     * Calculate distance between two points using Haversine formula
     * Returns distance in kilometers
     */
    public static function calculateDistance($lat1, $lon1, $lat2, $lon2): float
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return round($distance, 2);
    }

    /**
     * Get distance from this home to a given location
     */
    public function getDistance($latitude, $longitude): ?float
    {
        if (!$this->latitude || !$this->longitude) {
            return null;
        }

        return self::calculateDistance($latitude, $longitude, $this->latitude, $this->longitude);
    }

    /**
     * Scope to find nearby homes
     */
    public function scopeNearby(Builder $query, $latitude, $longitude, $radiusKm = 50): Builder
    {
        if (!$latitude || !$longitude) {
            return $query;
        }

        // Calculate distance for each home
        $homes = $query->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->map(function ($home) use ($latitude, $longitude) {
                $home->distance = $home->getDistance($latitude, $longitude);
                return $home;
            })
            ->filter(function ($home) use ($radiusKm) {
                return $home->distance !== null && $home->distance <= $radiusKm;
            })
            ->sortBy('distance');

        // Get IDs in order
        $ids = $homes->pluck('id')->toArray();

        if (empty($ids)) {
            return $query->whereRaw('1 = 0'); // Return empty result
        }

        // Return query ordered by distance
        return $query->whereIn('id', $ids)
            ->orderByRaw('FIELD(id, ' . implode(',', $ids) . ')');
    }

    # endregion
}
