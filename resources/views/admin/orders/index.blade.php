@extends('layouts.admin.admin', ['title' => __('title.orders'), 'active' => 'orders'])

@section('content')
    <x-admin.search-card route="{{ route('admin.orders.index') }}">
        <div class="col-12 col-md-3 mt-2">
            <label for="id">@lang('title.id')</label>
            <input type="text" class="form-control" name="id" id="id" value="{{ request('id') }}">
        </div>
        <div class="col-12 col-md-3 mt-2">
            <label for="status">@lang('title.status')</label>
            <select name="status" id="status" class="form-control">
                <option value="">@lang('title.select')</option>
                @foreach(\App\Models\Order::STATUSES as $status)
                    <option value="{{ $status['value'] }}" @if($status['value'] == request('status')) selected @endif>{{ $status['fa_text'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-3 mt-2">
            <label for="renter">@lang('title.guest')</label>
            <user-input
                @if(request()->filled('renter'))
                old="{{ request('renter') }}"
                @endif
                name="renter"
                route="{{ route('admin.ajax.users') }}"
                placeholder="@lang('title.select_guest')"
                select_label="@lang('title.select')"
                selected_label="@lang('title.selected')"
                deselect_label="@lang('title.remove')"
                no_result_text="@lang('text.empty_result')"
                no_options_text="@lang('text.empty_list')"
            ></user-input>
        </div>
        <div class="col-12 col-md-3 mt-2">
            <label for="owner">@lang('title.owner')</label>
            <user-input
                @if(request()->filled('owner'))
                old="{{ request('owner') }}"
                @endif
                name="owner"
                route="{{ route('admin.ajax.users') }}"
                placeholder="@lang('title.select_owner')"
                select_label="@lang('title.select')"
                selected_label="@lang('title.selected')"
                deselect_label="@lang('title.remove')"
                no_result_text="@lang('text.empty_result')"
                no_options_text="@lang('text.empty_list')"
            ></user-input>
        </div>
    </x-admin.search-card>

    <x-admin.card
        title="{{ __('title.orders') }}">

        <x-slot name="buttonText">
            <i class="fa fa-plus"></i>
        </x-slot>

        <div class="table-responsive scrollbar">
            @if($orders->isEmpty())
                <x-admin.empty-message></x-admin.empty-message>
            @else
                <table class="table table-hover table-striped overflow-hidden">
                    <thead>
                        <tr>
                            <th scope="col">@lang('title.id')</th>
                            <th scope="col">@lang('title.home')</th>
                            <th scope="col">@lang('title.owner')</th>
                            <th scope="col">@lang('title.guest')</th>
                            <th scope="col">@lang('title.status')</th>
                            <th scope="col">@lang('title.rent_date')</th>
                            <th scope="col">@lang('title.request_date')</th>
                            <th class="text-end" scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $order)
                        <tr class="align-middle">
                            <td class="text-nowrap">{{ $order->id }}</td>
                            <td class="text-nowrap">
                                <div class="d-flex align-items-center">
                                    @if($order->home->images->isNotEmpty())
                                        <img width="75" src="{{ $order->home->images->first()->image_path }}" alt="{{ $order->home->title }}">
                                    @endif
                                    <div class="ms-2">{{ $order->home->name }}</div>
                                </div>
                            </td>
                            <td class="text-nowrap">{{ $order->owner->full_name }}</td>
                            <td class="text-nowrap">{{ $order->renter->full_name }}</td>
                            <td class="text-nowrap">{{ $order->status() }}</td>
                            <td class="text-nowrap">{{ $order->period_text }}</td>
                            <td class="text-nowrap">{{ $order->persianCreatedAt() }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn p-0" data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('title.show')">
                                    <span class="text-500 fas fa-eye"></span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>

    </x-admin.card>
@endsection
