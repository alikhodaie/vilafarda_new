@extends('layouts.admin.admin', ['title' => __('title.subscribers'), 'active' => 'newsletter-subscribers'])

@section('content')
    <x-admin.search-card route="{{ route('admin.newsletter.subscribers.index') }}">
        <div class="col-12 col-md-6 mt-2">
            <label for="id">@lang('title.id')</label>
            <input type="text" class="form-control" name="id" id="id" value="{{ request('id') }}">
        </div>
        <div class="col-12 col-md-6 mt-2">
            <label for="email">@lang('title.email')</label>
            <input type="text" class="form-control" name="email" id="email" value="{{ request('email') }}">
        </div>
    </x-admin.search-card>

    <x-admin.card
        title="{{ __('title.subscribers') }}">

        <x-slot name="buttonText">
            <i class="fa fa-plus"></i>
        </x-slot>

        <div class="table-responsive scrollbar">
            @if($subscribers->isEmpty())
                <x-admin.empty-message></x-admin.empty-message>
            @else
                <table class="table table-hover table-striped overflow-hidden">
                    <thead>
                    <tr>
                        <th scope="col">@lang('title.id')</th>
                        <th scope="col">@lang('title.email')</th>
                        <th scope="col">@lang('title.link')</th>
                        <th class="text-end" scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($subscribers as $subscriber)
                        <tr class="align-middle">
                            <td class="text-nowrap">{{ $subscriber->id }}</td>
                            <td class="text-nowrap"><span style="direction: ltr">{{ $subscriber->email }}</span></td>
                            <td class="text-nowrap"><a style="direction: ltr" href="{{ $subscriber->link }}">{{ $subscriber->link }}</a></td>
                            <td class="text-end">
                                @can('delete', $subscriber)
                                    <delete-modal
                                        route="{{ route('admin.newsletter.subscribers.destroy', $subscriber->id) }}"
                                        csrf="{{ csrf_token() }}"
                                        title="@lang('title.delete subscriber')"
                                        text="@lang('text.delete subscriber')"
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
                    {{ $subscribers->links() }}
                </div>
            @endif
        </div>
    </x-admin.card>
@endsection
