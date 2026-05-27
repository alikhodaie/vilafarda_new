@extends('layouts.admin.admin', ['title' => __('title.newsletter'), 'active' => 'newsletter'])

@section('content')
    <x-admin.card
        title="{{ __('title.newsletter') }}"
        canSeeButton="{{ auth()->user()->can('create', \App\Models\Newsletter::class) }}"
        buttonLink="{{ route('admin.newsletter.create') }}">

        <x-slot name="buttonText">
            <i class="fa fa-plus"></i>
        </x-slot>

        <div class="table-responsive scrollbar">
            @if($newsletter->isEmpty())
                <x-admin.empty-message></x-admin.empty-message>
            @else
                <table class="table table-hover table-striped overflow-hidden">
                    <thead>
                    <tr>
                        <th scope="col">@lang('title.id')</th>
                        <th scope="col">@lang('title.title')</th>
                        <th class="text-end" scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($newsletter as $item)
                        <tr class="align-middle">
                            <td class="text-nowrap">{{ $item->id }}</td>
                            <td class="text-nowrap">{{ $item->title }}</td>
                            <td class="text-end">
                                @can('delete', $item)
                                    <delete-modal
                                        route="{{ route('admin.newsletter.destroy', $item->id) }}"
                                        csrf="{{ csrf_token() }}"
                                        title="@lang('title.delete newsletter')"
                                        text="@lang('text.delete newsletter')"
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
                    {{ $newsletter->links() }}
                </div>
            @endif
        </div>
    </x-admin.card>
@endsection
