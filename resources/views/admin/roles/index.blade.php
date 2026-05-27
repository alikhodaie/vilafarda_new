@extends('layouts.admin.admin', ['title' => __('title.roles'), 'active' => 'roles'])

@section('content')
    <x-admin.search-card route="{{ route('admin.roles.index') }}">
        <div class="col-12 col-md-4 mt-2">
            <label for="id">@lang('title.id')</label>
            <input type="text" class="form-control" name="id" id="id" value="{{ request('id') }}">
        </div>
        <div class="col-12 col-md-4 mt-2">
            <label for="title">@lang('title.title')</label>
            <input type="text" class="form-control" name="title" id="title" value="{{ request('title') }}">
        </div>
    </x-admin.search-card>

    <x-admin.card
        title="{{ __('title.roles') }}"
        canSeeButton="{{ auth()->user()->can('create', \Spatie\Permission\Models\Role::class) }}"
        buttonLink="{{ route('admin.roles.create') }}">

        <x-slot name="buttonText">
            <i class="fa fa-plus"></i>
        </x-slot>

        <div class="table-responsive scrollbar">
            @if($roles->isEmpty())
                <x-admin.empty-message></x-admin.empty-message>
            @else
                <table class="table table-hover table-striped overflow-hidden">
                    <thead>
                        <tr>
                            <th scope="col">@lang('title.id')</th>
                            <th scope="col">@lang('title.title')</th>
                            <th scope="col">@lang('title.admin count')</th>
                            <th scope="col">@lang('title.permission count')</th>
                            <th class="text-end" scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($roles as $role)
                        <tr class="align-middle">
                            <td class="text-nowrap">{{ $role->id }}</td>
                            <td class="text-nowrap">{{ $role->fa_name }}</td>
                            <td class="text-nowrap">{{ $role->users->count() }}</td>
                            <td class="text-nowrap">{{ $role->permissions->count() }}</td>
                            <td class="text-end">
                                @can('update', $role)
                                    <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn p-0" data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('title.edit')">
                                        <span class="text-500 fas fa-edit"></span>
                                    </a>
                                @endcan
                                @can('delete', $role)
                                    <delete-modal
                                        route="{{ route('admin.roles.destroy', $role->id) }}"
                                        csrf="{{ csrf_token() }}"
                                        title="@lang('title.delete role')"
                                        text="@lang('text.delete role')"
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
                    {{ $roles->links() }}
                </div>
            @endif
        </div>
    </x-admin.card>
@endsection
