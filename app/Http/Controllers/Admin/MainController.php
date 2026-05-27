<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\HomeStatisticsService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Morilog\Jalali\Jalalian;

class MainController extends Controller
{
    public function index(Request $request)
    {
        $chartDays = max(7, min(90, (int) $request->input('chart_days', 30)));
        $cacheKey = 'admin.home_charts.'.$chartDays.'.'.md5($request->getQueryString() ?? '');

        $homeCharts = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($request, $chartDays) {
            return app(HomeStatisticsService::class)->buildAdminCharts($request, $chartDays);
        });

        return view('admin.index', compact('homeCharts', 'chartDays'));
    }

    public function orderCount(Request $request)
    {
        $sub_count = $request->get('count', 6);
        $type = $request->get('type', 'weekly');

        $sub_method = match($type) {
            'weekly' => 'subDays',
            'monthly' => 'subMonths',
            'yearly' => 'subYears',
        };
        $first_day_method = match($type) {
            'weekly' => 'getFirstDayOfWeek',
            'monthly' => 'getFirstDayOfMonth',
            'yearly' => 'getFirstDayOfYear',
        };
        $last_day_method = match($type) {
            'weekly' => 'getEndDayOfWeek',
            'monthly' => 'getEndDayOfMonth',
            'yearly' => 'getEndDayOfYear',
        };
        $format = match($type) {
            'weekly' => 'Y/m/d',
            'monthly' => 'Y/m',
            'yearly' => 'Y',
        };

        $start_date = Jalalian::now()->$sub_method((($sub_method === 'subDays') ? (7 * $sub_count): $sub_count) - 1);
        $start_date = $start_date->$first_day_method()
            ->subHours($start_date->getHour())
            ->subMinutes($start_date->getMinute())
            ->subSeconds($start_date->getSecond())
            ->toCarbon();

        $end_date = now();

        $orders = Order::query()
            ->selectRaw('DATE(created_at) as date, COUNT(id) as count')
            ->where('status', Order::DONE)
            ->whereBetween('created_at', [$start_date, $end_date])
            ->groupBy('date')
            ->get()
            ->map(function ($item) {
                $item->date = Jalalian::forge($item->date)->format('Y-m-d');
                return $item;
            });

        $data = [];
        for ($i = 0; $i < $sub_count; $i++)
        {
            $date = Jalalian::now();
            if ($i > 0){
                $end_sub_count = ($sub_method === 'subDays') ? (7 * $i): $i;
                $date = $date->$sub_method($end_sub_count);
            }
            $start = $date->$first_day_method();
            $end = $date->$last_day_method();
            $count = $orders->whereBetween('date', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->sum('count');

            $formatted_date = ($sub_method === 'subDays')
                ? $start->format($format). ' - ' .$end->format($format)
                : $date->format($format);

            $data[] = [
                'date' => $formatted_date,
                'count' => $count
            ];
        }

        return response()->json($data);
    }
}
