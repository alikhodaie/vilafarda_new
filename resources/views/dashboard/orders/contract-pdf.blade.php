<!DOCTYPE html>
<html>
<style>
    @page {
        header: page-header;
        footer: page-footer;
    }
    body {
        font-family: 'yekan';
        direction: rtl;
    }
    table, thead, tbody {
        width: 100%;
    }
    .text-left {
        text-align: left
    }
    .text-right {
        text-align: right
    }
    .text-center {
        text-align: center
    }
    th, td {
        /*border: 1px black solid;*/
    }
    hr {
        margin: 30px 0;
    }
</style>
<htmlpageheader name="page-header">
</htmlpageheader>
<body>
    <table>
        <thead>
            <tr>
                <td>@lang('title.reservation_document')</td>
                <td class="text-left">
                    <div class="text-left">
                        <img width="150" src="{{ settingFilePath('app:logo') }}" alt="{{ config('app.name') }}" />
                    </div>
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    @lang('title.reservation_code'): {{ $order->id }}
                </td>
            </tr>
        </tbody>
    </table>
    <hr>
    <table>
        <thead>
            <tr>
                <th>@lang('title.date_enter')</th>
                <th>@lang('title.date_quit')</th>
                <th>@lang('title.time_stay')</th>
                <th>@lang('title.count_people')</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th>
                    {{ $order->start_date }}
                </th>
                <th>
                    {{ $order->end_date }}
                </th>
                <th>
                    {{ $order->count_days }}
                </th>
                <th>
                    {{ $order->count_guest }}
                </th>
            </tr>
        </tbody>
    </table>
    <hr>
    <table>
        <tr>
            <th class="text-right">@lang('title.owner')</th>
            <td rowspan="3">
                <div class="text-right">
                    <img width="100" src="{{ $order->owner->avatar_path }}" alt="{{ $order->owner->fullname }}" />
                </div>
            </td>
            <th class="text-right">@lang('title.guest')</th>
            <td rowspan="3">
                <div class="text-right">
                    <img width="100" src="{{ $order->renter->avatar_path }}" alt="{{ $order->renter->fullname }}" />
                </div>
            </td>
        </tr>

        <tr class="text-right">
            <td>@lang('title.name'): {{ $order->owner->fullname }}</td>
            <td>@lang('title.name'): {{ $order->renter->fullname }}</td>
        </tr>
        <tr class="text-right">
            <td>@lang('title.mobile'): {!! ($order->isPreContract()) ? hidden($order->owner->mobile): "<a href='tel:{$order->owner->mobile}'>{$order->owner->mobile}</a>" !!}</td>
            <td>@lang('title.mobile'): {!! ($order->isPreContract()) ? hidden($order->renter->mobile): "<a href='tel:{$order->renter->mobile}'>{$order->renter->mobile}</a>" !!}</td>
        </tr>
    </table>
    <hr>
    <table>
        <tr class="text-right">
            <th class="text-right">@lang('title.place_information')</th>
            <th class="text-right">@lang('title.reserve_cancel_policy'): {{ $order->home->rejectPolicy() }}</th>
        </tr>
        <tr class="text-right">
            <td>@lang('title.title'): <a href='{{ $order->home->link }}' target="_blank">{{ $order->home->name }}</a></td>
            <td>{{ $order->home->rejectPolicy('description') }}</td>
        </tr>
        <tr class="text-right">
            <td>@lang('title.place_code'): {{ $order->home->id }}</td>
            <th class="text-right">@lang('title.place_policy')</th>
        </tr>
        <tr class="text-right">
            <td style="padding-left: 100px">@lang('title.address'): {{ $order->home->address_text }}</td>
            <td>{{ $order->home->rules }}</td>
        </tr>
    </table>
    <hr>
    <table>
        <tr class="text-right">
            <th colspan="2">@lang('title.bill')</th>
        </tr>
        <tr class="text-center">
            <td>{{ $order->period_text }}</td>
            <td>{{ number_format($order->price) }} @lang('title.toman')</td>
        </tr>
        <tr class="text-center">
            <td>@lang('title.extra_person')</td>
            <td>{{ number_format($order->extra_guest) }}</td>
        </tr>
        <tr class="text-center">
            <td>@lang('title.bill_price')</td>
            <td>{{ number_format($order->price) }} @lang('title.toman')</td>
        </tr>
        <tr class="text-center">
            <td>@lang('title.payed_price')</td>
            <td>{{ number_format(($order->paid_at) ? $order->price: 0) }} @lang('title.toman')</td>
        </tr>
    </table>
    <hr>
    <table class="text-right">
        <tr>
            <th>@lang('title.health_advice')</th>
        </tr>
        <tr>
            <td>{{ setting('reservation:health-advice') }}</td>
        </tr>
    </table>
</body>
</html>
