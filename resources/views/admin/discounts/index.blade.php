@extends('layouts.admin.admin', ['title' => __('title.discounts'), 'active' => 'discounts'])

@section('content')
    <x-admin.card
        title="{{ __('title.discounts') }}"
        canSeeButton="{{ auth()->user()->can('create', \App\Models\Discount::class) }}"
        buttonLink="{{ route('admin.discounts.create') }}">

        <x-slot name="buttonText">
            <i class="fa fa-plus"></i>
        </x-slot>

        <div class="table-responsive scrollbar">
            @if($discounts->isEmpty())
                <x-admin.empty-message></x-admin.empty-message>
            @else
                <table class="table table-hover table-striped overflow-hidden">
                    <thead>
                    <tr>
                        <th scope="col">@lang('title.id')</th>
                        <th scope="col">@lang('title.code')</th>
                        <th scope="col">@lang('title.amount')</th>
                        <th scope="col">@lang('title.user_type')</th>
                        <th scope="col">@lang('title.count_users')</th>
                        <th scope="col">@lang('title.count_usage')</th>
                        <th scope="col">@lang('title.expired_at')</th>
                        <th scope="col">@lang('title.created_at')</th>
                        <th class="text-end" scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($discounts as $discount)
                        <tr class="align-middle">
                            <td class="text-nowrap">{{ $discount->id }}</td>
                            <td class="text-nowrap">{{ $discount->code }}</td>
                            <td class="text-nowrap">{{ number_format($discount->amount) }} {{ $discount->type('symbol') }}</td>
                            <td class="text-nowrap">{{ $discount->userType() }}</td>
                            <td class="text-nowrap">{{ $discount->count_users }}</td>
                            <td class="text-nowrap">{{ $discount->count_usage }}</td>
                            <td class="text-nowrap">{{ $discount->persianDate('expired_at', '%d %B %Y') }}</td>
                            <td class="text-nowrap">{{ $discount->persianCreatedAt('%d %B %Y') }}</td>
                            <td class="text-end">
                                @if($discount->count_users !== $discount->count_usage)
                                @can('update', $discount)
                                    <a href="{{ route('admin.discounts.edit.use', $discount->id) }}" class="btn p-0"
                                       data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('title.use user')">
                                        <span class="text-500 fas fa-user"></span>
                                    </a>
                                @endcan
                                @endif
                                @can('update', $discount)
                                    <a href="{{ route('admin.discounts.edit', $discount->id) }}" class="btn p-0"
                                       data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('title.edit')">
                                        <span class="text-500 fas fa-edit"></span>
                                    </a>
                                @endcan
                                @can('delete', $discount)
                                    <delete-modal
                                        route="{{ route('admin.discounts.destroy', $discount->id) }}"
                                        csrf="{{ csrf_token() }}"
                                        title="@lang('title.delete discount')"
                                        text="@lang('text.delete discount')"
                                        button_hover_text="@lang('title.delete')"
                                        button_cancel_text="@lang('title.cancel')"
                                        button_submit_text="@lang('title.delete')"
                                    ></delete-modal>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{ $discounts->links() }}
                </div>
            @endif
        </div>
    </x-admin.card>
@endsection
