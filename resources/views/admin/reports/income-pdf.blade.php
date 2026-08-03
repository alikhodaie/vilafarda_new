<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<style>
    @page {
        header: page-header;
        footer: page-footer;
    }
    body {
        font-family: 'yekan';
        direction: rtl;
        color: #222;
        font-size: 12px;
    }
    .title {
        font-size: 18px;
        font-weight: bold;
    }
    .muted {
        color: #666;
        font-size: 11px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }
    thead th {
        background-color: #f2f2f2;
        border: 1px solid #cccccc;
        padding: 6px 4px;
        font-weight: bold;
        text-align: center;
    }
    tbody td {
        border: 1px solid #dddddd;
        padding: 6px 4px;
        text-align: center;
    }
    tfoot td {
        border: 1px solid #cccccc;
        padding: 7px 4px;
        font-weight: bold;
        background-color: #faf3df;
        text-align: center;
    }
    .text-right {
        text-align: right;
    }
    .text-left {
        text-align: left;
    }
    hr {
        margin: 16px 0;
        border: none;
        border-top: 1px solid #e2e2e2;
    }
</style>
<htmlpageheader name="page-header"></htmlpageheader>
<htmlpagefooter name="page-footer">
    <div class="muted text-center" style="border-top: 1px solid #e2e2e2; padding-top: 6px;">
        {{ config('app.name') }} — تاریخ تولید گزارش: {{ $report['generated_at'] }}
    </div>
</htmlpagefooter>
<body>
    <table style="border: none;">
        <tr>
            <td class="text-right" style="border: none;">
                <div class="title">گزارش درآمد یک‌ساله (کمیسیون)</div>
                <div class="muted">
                    بازه: از {{ $report['from'] }} تا {{ $report['to'] }}
                </div>
                <div class="muted">
                    تعداد اقامتگاه‌های محاسبه‌شده: {{ number_format($report['homes_count']) }}
                </div>
            </td>
            <td class="text-left" style="border: none;">
                @if(!empty($logoPath))
                    <img width="130" src="{{ $logoPath }}" alt="{{ config('app.name') }}" />
                @endif
            </td>
        </tr>
    </table>

    <hr>

    <table>
        <thead>
            <tr>
                <th style="width: 8%;">ردیف</th>
                <th style="width: 22%;">ماه</th>
                <th style="width: 16%;">تعداد سفارش</th>
                <th style="width: 27%;">مبلغ کل فروش (تومان)</th>
                <th style="width: 27%;">درآمد کمیسیون (تومان)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($report['months'] as $index => $month)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $month['label'] }}</td>
                    <td>{{ number_format($month['orders']) }}</td>
                    <td>{{ number_format($month['gross']) }}</td>
                    <td>{{ number_format($month['commission']) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">مجموع</td>
                <td>{{ number_format($report['totals']['orders']) }}</td>
                <td>{{ number_format($report['totals']['gross']) }}</td>
                <td>{{ number_format($report['totals']['commission']) }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
