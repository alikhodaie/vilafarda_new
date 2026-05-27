@php
    $metricLabels = [
        'income' => 'درآمد (تومان)',
        'views' => 'بازدید',
        'clicks' => 'کلیک',
    ];
@endphp

{{-- v-pre: ECharts must not be compiled/replaced by Vue (#app) on the admin dashboard --}}
<div v-pre class="border-bottom px-3 py-3 bg-white" id="adminHomeChartsPanel">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <div>
            <h6 class="mb-1 fw-bold">آمار اقامتگاه‌ها</h6>
            <small class="text-muted">
                {{ number_format($homeCharts['homes_count']) }} اقامتگاه —
                {{ $chartDays }} روز اخیر
            </small>
        </div>
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <select id="adminHomeChartDays" class="form-select form-select-sm" style="width: auto;">
                <option value="7" @selected($chartDays === 7)>۷ روز</option>
                <option value="30" @selected($chartDays === 30)>۳۰ روز</option>
                <option value="90" @selected($chartDays === 90)>۹۰ روز</option>
            </select>
            <select id="adminHomeChartType" class="form-select form-select-sm" style="width: auto;">
                <option value="bar">میله‌ای</option>
                <option value="line">خطی</option>
                <option value="pie">دایره‌ای</option>
            </select>
        </div>
    </div>

    <ul class="nav nav-tabs nav-tabs-sm mb-3" id="adminHomeChartMetricTabs" role="tablist">
        @foreach($metricLabels as $key => $label)
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                        type="button"
                        data-metric="{{ $key }}"
                        role="tab">
                    {{ $label }}
                    <span class="badge bg-soft-primary text-primary ms-1 admin-home-chart-total" data-metric-total="{{ $key }}">
                        @if($key === 'income')
                            {{ number_format($homeCharts['totals'][$key]) }}
                        @else
                            {{ number_format($homeCharts['totals'][$key]) }}
                        @endif
                    </span>
                </button>
            </li>
        @endforeach
    </ul>

    <div id="adminHomeStatisticsChart" style="height: 360px; width: 100%;"></div>
</div>

@push('after-vue')
<script>
(function () {
    const chartPayload = @json($homeCharts);
    const metricLabels = @json($metricLabels);
    let currentMetric = 'income';
    let currentType = 'bar';
    let chartInstance = null;

    function chartEl() {
        return document.getElementById('adminHomeStatisticsChart');
    }

    function formatNumber(value) {
        return new Intl.NumberFormat('fa-IR').format(value);
    }

    function buildOptions() {
        const isPie = currentType === 'pie';
        const seriesLabel = metricLabels[currentMetric] || currentMetric;

        if (isPie) {
            const pieData = chartPayload.pie[currentMetric] || [];
            return {
                tooltip: {
                    trigger: 'item',
                    formatter: function (params) {
                        return params.name + '<br>' + seriesLabel + ': ' + formatNumber(params.value)
                            + (params.percent ? ' (' + params.percent + '%)' : '');
                    }
                },
                legend: {
                    type: 'scroll',
                    bottom: 0,
                    textStyle: { fontFamily: 'inherit' }
                },
                series: [{
                    name: seriesLabel,
                    type: 'pie',
                    radius: ['38%', '68%'],
                    center: ['50%', '45%'],
                    data: pieData.length ? pieData : [{ name: 'بدون داده', value: 0 }],
                    emphasis: {
                        itemStyle: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.2)'
                        }
                    }
                }]
            };
        }

        const values = chartPayload.series[currentMetric] || [];
        return {
            tooltip: {
                trigger: 'axis',
                formatter: function (params) {
                    const item = params[0];
                    return (item.axisValueLabel || item.name) + '<br>' + seriesLabel + ': ' + formatNumber(item.value);
                }
            },
            grid: {
                left: '3%',
                right: '3%',
                bottom: '8%',
                containLabel: true
            },
            xAxis: {
                type: 'category',
                data: chartPayload.labels,
                axisLabel: {
                    rotate: chartPayload.labels.length > 14 ? 45 : 0,
                    fontFamily: 'inherit'
                }
            },
            yAxis: {
                type: 'value',
                axisLabel: {
                    formatter: function (value) { return formatNumber(value); },
                    fontFamily: 'inherit'
                }
            },
            series: [{
                name: seriesLabel,
                type: currentType,
                smooth: currentType === 'line',
                data: values,
                itemStyle: { color: '#D39D1A' },
                areaStyle: currentType === 'line' ? { color: 'rgba(211, 157, 26, 0.15)' } : undefined,
                barMaxWidth: 42
            }]
        };
    }

    function renderChart() {
        const el = chartEl();
        if (!el || typeof window.echarts === 'undefined') {
            return;
        }

        if (chartInstance) {
            chartInstance.dispose();
            chartInstance = null;
        }

        chartInstance = window.echarts.init(el);
        chartInstance.setOption(buildOptions(), true);
    }

    if (!window.__adminHomeChartResizeBound) {
        window.__adminHomeChartResizeBound = true;
        window.addEventListener('resize', function () {
            if (chartInstance) {
                chartInstance.resize();
            }
        });
    }

    const panel = document.getElementById('adminHomeChartsPanel');
    if (!panel) {
        return;
    }

    document.querySelectorAll('#adminHomeChartMetricTabs [data-metric]').forEach(function (tab) {
        tab.addEventListener('click', function () {
            document.querySelectorAll('#adminHomeChartMetricTabs .nav-link').forEach(function (el) {
                el.classList.remove('active');
            });
            this.classList.add('active');
            currentMetric = this.dataset.metric;
            renderChart();
        });
    });

    document.getElementById('adminHomeChartType').addEventListener('change', function () {
        currentType = this.value;
        renderChart();
    });

    document.getElementById('adminHomeChartDays').addEventListener('change', function () {
        const url = new URL(window.location.href);
        url.searchParams.set('chart_days', this.value);
        window.location.href = url.toString();
    });

    renderChart();
})();
</script>
@endpush
