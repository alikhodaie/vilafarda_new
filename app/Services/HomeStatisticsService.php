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
            $orderIncome = (int) ($incomeFromOrders[$key] ?? 0);
            $storedIncome = (int) (optional($row)->income ?? 0);
            $income = max($storedIncome, $orderIncome);

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

        $paidStatuses = [
            Order::DONE,
            Order::IN_RENT,
            Order::WAITING_FOR_RENTER,
            Order::AWAITING_PAYMENT,
        ];

        return Order::query()
            ->whereIn('home_id', $homeIds)
            ->whereIn('status', $paidStatuses)
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('paid_at', [$start, $end])
                    ->orWhere(function ($inner) use ($start, $end) {
                        $inner->whereNull('paid_at')
                            ->whereBetween('created_at', [$start, $end]);
                    });
            })
            ->selectRaw('DATE(COALESCE(paid_at, created_at)) as stat_date, SUM(price) as total')
            ->groupBy('stat_date')
            ->pluck('total', 'stat_date')
            ->map(fn ($total) => (int) $total)
            ->all();
    }

    protected function topHomesPie(Collection $homeIds, Carbon $start, Carbon $end, string $metric): array
    {
        if ($homeIds->isEmpty()) {
            return [];
        }

        if ($metric === 'income') {
            $paidStatuses = [
                Order::DONE,
                Order::IN_RENT,
                Order::WAITING_FOR_RENTER,
                Order::AWAITING_PAYMENT,
            ];

            $rows = Order::query()
                ->whereIn('home_id', $homeIds)
                ->whereIn('status', $paidStatuses)
                ->where(function ($query) use ($start, $end) {
                    $query->whereBetween('paid_at', [$start, $end])
                        ->orWhere(function ($inner) use ($start, $end) {
                            $inner->whereNull('paid_at')
                                ->whereBetween('created_at', [$start, $end]);
                        });
                })
                ->select('home_id', DB::raw('SUM(price) as total'))
                ->groupBy('home_id')
                ->orderByDesc('total')
                ->limit(8)
                ->with('home:id,name')
                ->get();

            return $rows->map(fn ($row) => [
                'name' => $row->home?->name ?: ('#'.$row->home_id),
                'value' => (int) $row->total,
            ])->values()->all();
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
