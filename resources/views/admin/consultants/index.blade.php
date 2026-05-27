@extends('layouts.admin.admin', ['title' => __('title.consultants'), 'active' => 'consultants'])

@section('content')
    <x-admin.card
        title="{{ __('title.consultants') }}"
        canSeeButton="{{ auth()->user()->can('create', \App\Models\Consultant::class) }}"
        buttonLink="{{ route('admin.consultants.create') }}">

        <x-slot name="buttonText">
            <i class="fa fa-plus"></i>
        </x-slot>

        <div class="table-responsive scrollbar">
            @if($consultants->isEmpty())
                <x-admin.empty-message></x-admin.empty-message>
            @else
                <table class="table table-hover table-striped overflow-hidden">
                    <thead>
                    <tr>
                        <th scope="col">@lang('title.id')</th>
                        <th scope="col">@lang('title.name')</th>
                        <th scope="col">@lang('title.province') / @lang('title.city')</th>
                        <th class="text-end" scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($consultants as $consultant)
                        <tr class="align-middle">
                            <td class="text-nowrap">{{ $consultant->id }}</td>
                            <td class="text-nowrap">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-xl">
                                    <img src="{{ $consultant->image_path }}" alt="{{ $consultant->name }}" class="rounded-circle" onerror="this.src='{{ asset('assets/images/avatar.jpg') }}'"></div>
                                    <div class="ms-2">{{ $consultant->name }}</div>
                                </div>
                            </td>
                            <td class="text-nowrap">{{ $consultant->province->name }} / {{ $consultant->city->name }}</td>
                            <td class="text-end">
                                @can('update', $consultant)
                                    <a href="{{ route('admin.consultants.edit', $consultant->id) }}" class="btn p-0" data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('title.edit')">
                                        <span class="text-500 fas fa-edit"></span>
                                    </a>
                                @endcan
                                @can('delete', $consultant)
                                    <delete-modal
                                        route="{{ route('admin.consultants.destroy', $consultant->id) }}"
                                        csrf="{{ csrf_token() }}"
                                        title="@lang('title.delete consultant')"
                                        text="@lang('text.delete consultant')"
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
            @endif
        </div>

    </x-admin.card>
@endsection
