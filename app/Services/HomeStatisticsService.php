<?php

namespace App\Services;

use App\Models\Home;
use App\Models\HomeDailyStat;
use App\Models\Order;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Morilog\Jalali\Jalalian;

class HomeStatisticsService
{
    public function recordView(Home $home): void
    {
        $this->incrementStat($home, 'views');
    }

    public function recordClick(Home $home): void
    {
        $this->incrementStat($home, 'clicks');
    }

    public function recordIncome(Home $home, int $amount, ?Carbon $date = null): void
    {
        if ($amount <= 0) {
            return;
        }

        $date = ($date ?? now())->toDateString();

        $stat = HomeDailyStat::query()->firstOrCreate(
            ['home_id' => $home->id, 'stat_date' => $date],
            ['views' => 0, 'clicks' => 0, 'income' => 0]
        );

        $stat->increment('income', $amount);
    }

    public function buildAdminCharts(Request $request, int $days = 30): array
    {
        $days = max(7, min(90, $days));
        $homeIds = $this->filteredHomeIds($request);

        $end = now()->endOfDay();
        $start = now()->subDays($days - 1)->startOfDay();

        $dailyAggregates = HomeDailyStat::query()
            ->whereIn('home_id', $homeIds)
            ->whereBetween('stat_date', [$start->toDateString(), $end->toDateString()])
            ->groupBy('stat_date')
            ->orderBy('stat_date')
            ->get([
                DB::raw('stat_date as stat_date'),
                DB::raw('SUM(views) as views'),
                DB::raw('SUM(clicks) as clicks'),
                DB::raw('SUM(income) as income'),
            ])
            ->keyBy(fn ($row) => Carbon::parse($row->stat_date)->toDateString());

        $incomeFromOrders = $this->incomeByDateFromOrders($homeIds, $start, $end);

        $labels = [];
        $series = [
            'income' => [],
            'views' => [],
            'clicks' => [],
        ];

        foreach (CarbonPeriod::create($start, $end) as $date) {
            $key = $date->toDateString();
            $row = $dailyAggregates->get($key);
            // درآمد ادمین = کمیسیون کسر شده از مبلغ سفارش‌ها، نه کل مبلغ فروش
            $income = (int) ($incomeFromOrders[$key] ?? 0);

            $labels[] = Jalalian::fromCarbon($date)->format('Y/m/d');
            $series['income'][] = $income;
            $series['views'][] = (int) (optional($row)->views ?? 0);
            $series['clicks'][] = (int) (optional($row)->clicks ?? 0);
        }

        return [
            'labels' => $labels,
            'series' => $series,
            'pie' => [
                'income' => $this->topHomesPie($homeIds, $start, $end, 'income'),
                'views' => $this->topHomesPie($homeIds, $start, $end, 'views'),
                'clicks' => $this->topHomesPie($homeIds, $start, $end, 'clicks'),
            ],
            'totals' => [
                'income' => array_sum($series['income']),
                'views' => array_sum($series['views']),
                'clicks' => array_sum($series['clicks']),
            ],
            'days' => $days,
            'homes_count' => $homeIds->count(),
        ];
    }

    /**
     * گزارش یک‌ساله‌ی درآمد (کمیسیون) ادمین به تفکیک ماه شمسی.
     * برای خروجی PDF استفاده می‌شود.
     */
    public function buildYearlyIncomeReport(?Request $request = null): array
    {
        $homeIds = $request
            ? $this->filteredHomeIds($request)
            : Home::query()->where('is_draft', false)->pluck('id');

        // ۱۲ ماه شمسی اخیر (شامل ماه جاری) به‌صورت سطل‌های خالی
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = $i === 0 ? Jalalian::now() : Jalalian::now()->subMonths($i);
            $key = $month->format('Y/m');
            $months[$key] = [
                'label' => $key,
                'gross' => 0,
                'commission' => 0,
                'orders' => 0,
            ];
        }

        $start = Jalalian::now()->subMonths(11)->getFirstDayOfMonth()->toCarbon()->startOfDay();
        $end = now()->endOfDay();

        if ($homeIds->isNotEmpty()) {
            $rows = $this->paidOrdersQuery($homeIds, $start, $end)
                ->selectRaw(
                    'DATE(COALESCE(orders.paid_at, orders.created_at)) as stat_date, '
                    .'SUM(orders.price) as gross, '
                    .$this->commissionExpression().' as commission, '
                    .'COUNT(orders.id) as orders_count'
                )
                ->groupBy('stat_date')
                ->get();

            foreach ($rows as $row) {
                $key = Jalalian::fromCarbon(Carbon::parse($row->stat_date))->format('Y/m');

                if (! isset($months[$key])) {
                    continue;
                }

                $months[$key]['gross'] += (int) $row->gross;
                $months[$key]['commission'] += (int) $row->commission;
                $months[$key]['orders'] += (int) $row->orders_count;
            }
        }

        $months = array_values($months);

        return [
            'months' => $months,
            'totals' => [
                'gross' => array_sum(array_column($months, 'gross')),
                'commission' => array_sum(array_column($months, 'commission')),
                'orders' => array_sum(array_column($months, 'orders')),
            ],
            'from' => Jalalian::fromCarbon($start)->format('Y/m/d'),
            'to' => Jalalian::fromCarbon($end)->format('Y/m/d'),
            'generated_at' => Jalalian::now()->format('Y/m/d - H:i'),
            'homes_count' => $homeIds->count(),
        ];
    }

    protected function filteredHomeIds(Request $request): Collection
    {
        return Home::query()
            ->where('is_draft', false)
            ->when($this->hasHomeSearchFilters($request), fn ($query) => $query->search())
            ->pluck('id');
    }

    protected function hasHomeSearchFilters(Request $request): bool
    {
        return $request->filled('id')
            || $request->filled('name')
            || $request->filled('user')
            || $request->filled('status')
            || $request->filled('province')
            || $request->filled('city');
    }

    protected function incrementStat(Home $home, string $column): void
    {
        $date = now()->toDateString();

        $stat = HomeDailyStat::query()->firstOrCreate(
            ['home_id' => $home->id, 'stat_date' => $date],
            ['views' => 0, 'clicks' => 0, 'income' => 0]
        );

        $stat->increment($column);
    }

    protected function incomeByDateFromOrders(Collection $homeIds, Carbon $start, Carbon $end): array
    {
        if ($homeIds->isEmpty()) {
            return [];
        }

        return $this->paidOrdersQuery($homeIds, $start, $end)
            ->selectRaw('DATE(COALESCE(orders.paid_at, orders.created_at)) as stat_date, '.$this->commissionExpression().' as total')
            ->groupBy('stat_date')
            ->pluck('total', 'stat_date')
            ->map(fn ($total) => (int) $total)
            ->all();
    }

    /**
     * وضعیت‌هایی که به‌عنوان سفارش پرداخت‌شده/درآمدزا در نظر گرفته می‌شوند.
     */
    protected function paidOrderStatuses(): array
    {
        return [
            Order::DONE,
            Order::IN_RENT,
            Order::WAITING_FOR_RENTER,
            Order::AWAITING_PAYMENT,
        ];
    }

    /**
     * کوئری پایه سفارش‌های درآمدزا به همراه join با اقامتگاه‌ها (برای دسترسی به سیاست لغو/کمیسیون).
     */
    protected function paidOrdersQuery(Collection $homeIds, Carbon $start, Carbon $end)
    {
        return Order::query()
            ->join('homes', 'homes.id', '=', 'orders.home_id')
            ->whereIn('orders.home_id', $homeIds)
            ->whereIn('orders.status', $this->paidOrderStatuses())
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('orders.paid_at', [$start, $end])
                    ->orWhere(function ($inner) use ($start, $end) {
                        $inner->whereNull('orders.paid_at')
                            ->whereBetween('orders.created_at', [$start, $end]);
                    });
            });
    }

    /**
     * عبارت SQL محاسبه‌ی کمیسیون ادمین بر اساس درصد کمیسیونِ سیاست لغو هر اقامتگاه.
     * درصدها از تنظیمات خوانده و به‌صورت عدد صحیح داخل عبارت درج می‌شوند (ورودی امن).
     */
    protected function commissionExpression(): string
    {
        $easy = (int) setting('commission:easy', 0);
        $balanced = (int) setting('commission:balanced', 0);
        $strict = (int) setting('commission:strict', 0);

        return "SUM(orders.price * (CASE homes.reject_policy"
            ." WHEN '".Home::EASY."' THEN {$easy}"
            ." WHEN '".Home::BALANCED."' THEN {$balanced}"
            ." WHEN '".Home::STRICT."' THEN {$strict}"
            ." ELSE 0 END) / 100)";
    }

    protected function topHomesPie(Collection $homeIds, Carbon $start, Carbon $end, string $metric): array
    {
        if ($homeIds->isEmpty()) {
            return [];
        }

        if ($metric === 'income') {
            $rows = $this->paidOrdersQuery($homeIds, $start, $end)
                ->select('orders.home_id as home_id', DB::raw($this->commissionExpression().' as total'))
                ->groupBy('orders.home_id')
                ->orderByDesc('total')
                ->limit(8)
                ->with('home:id,name')
                ->get();

            return $rows->map(fn ($row) => [
                'name' => $row->home?->name ?: ('#'.$row->home_id),
                'value' => (int) $row->total,
            ])->filter(fn ($item) => $item['value'] > 0)->values()->all();
        }

        $column = $metric === 'clicks' ? 'clicks' : 'views';

        $rows = HomeDailyStat::query()
            ->whereIn('home_id', $homeIds)
            ->whereBetween('stat_date', [$start->toDateString(), $end->toDateString()])
            ->select('home_id', DB::raw("SUM($column) as total"))
            ->groupBy('home_id')
            ->orderByDesc('total')
            ->limit(8)
            ->with('home:id,name')
            ->get();

        return $rows->map(fn ($row) => [
            'name' => $row->home?->name ?: ('#'.$row->home_id),
            'value' => (int) $row->total,
        ])->filter(fn ($item) => $item['value'] > 0)->values()->all();
    }
}
