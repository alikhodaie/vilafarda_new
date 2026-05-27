@extends('layouts.admin.admin', ['title' => __('title.homes').' | '.__('title.safeties'), 'active' => 'homes-safeties'])

@section('content')
    <x-admin.search-card route="{{ route('admin.homes.safeties.index') }}">
        <div class="col-12 col-md-6 mt-2">
            <label for="id">@lang('title.id')</label>
            <input type="text" class="form-control" name="id" id="id" value="{{ request('id') }}">
        </div>
        <div class="col-12 col-md-6 mt-2">
            <label for="title">@lang('title.title')</label>
            <input type="text" class="form-control" name="title" id="title" value="{{ request('title') }}">
        </div>
    </x-admin.search-card>

    <x-admin.card
        title="{{ __('title.safeties') }}"
        canSeeButton="{{ auth()->user()->can('create', \App\Models\Safety::class) }}"
        buttonLink="{{ route('admin.homes.safeties.create') }}">

        <x-slot name="buttonText">
            <i class="fa fa-plus"></i>
        </x-slot>

        <div class="table-responsive scrollbar">
            @if($safeties->isEmpty())
                <x-admin.empty-message></x-admin.empty-message>
            @else
                <table class="table table-hover table-striped overflow-hidden">
                    <thead>
                        <tr>
                            <th scope="col">@lang('title.id')</th>
                            <th scope="col">@lang('title.title')</th>
                            <th scope="col">@lang('title.place_holder')</th>
                            <th class="text-end" scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($safeties as $safety)
                        <tr class="align-middle">
                            <td class="text-nowrap">{{ $safety->id }}</td>
                            <td class="text-nowrap">{{ $safety->title }}</td>
                            <td class="text-nowrap">{{ $safety->placeholder }}</td>
                            <td class="text-end">
                                @can('update', $safety)
                                    <a href="{{ route('admin.homes.safeties.edit', $safety->id) }}" class="btn p-0" data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('title.edit')">
                                        <span class="text-500 fas fa-edit"></span>
                                    </a>
                                @endcan
                                @can('delete', $safety)
                                    <delete-modal
                                        route="{{ route('admin.homes.safeties.destroy', $safety->id) }}"
                                        csrf="{{ csrf_token() }}"
                                        title="@lang('title.delete safety')"
                                        text="@lang('text.delete safety')"
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
                    {{ $safeties->links() }}
                </div>
            @endif
        </div>

    </x-admin.card>
@endsection
