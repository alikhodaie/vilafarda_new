@extends('layouts.admin.admin', ['title' => __('title.users'), 'active' => 'users'])

@section('content')
    <x-admin.search-card route="{{ route('admin.users.index') }}">
        <div class="col-12 col-md-4 mt-2">
            <label for="id">@lang('title.id')</label>
            <input type="text" class="form-control" name="id" id="id" value="{{ request('id') }}">
        </div>
        <div class="col-12 col-md-4 mt-2">
            <label for="name">@lang('title.name')</label>
            <input type="text" class="form-control" name="name" id="name" value="{{ request('name') }}">
        </div>
        <div class="col-12 col-md-4 mt-2">
            <label for="email">@lang('title.email')</label>
            <input type="text" class="form-control" name="email" id="email" value="{{ request('email') }}">
        </div>
        <div class="col-12 col-md-3 mt-2">
            <label for="mobile">@lang('title.mobile')</label>
            <input type="text" class="form-control" name="mobile" id="mobile" value="{{ request('mobile') }}">
        </div>
        <div class="col-12 col-md-3 mt-2">
            <label for="verified_mobile">@lang('title.verified mobile')</label>
            <select class="form-control" name="verified_mobile" id="verified_mobile">
                <option value="">@lang('title.select')</option>
                <option value="no" @if(request('verified_mobile') === 'no') selected @endif>@lang('title.no')</option>
                <option value="yes" @if(request('verified_mobile') === 'yes') selected @endif>@lang('title.yes')</option>
            </select>
        </div>
        <div class="col-12 col-md-3 mt-2">
            <label for="verified_email">@lang('title.verified email')</label>
            <select class="form-control" name="verified_email" id="verified_email">
                <option value="">@lang('title.select')</option>
                <option value="no" @if(request('verified_email') === 'no') selected @endif>@lang('title.no')</option>
                <option value="yes" @if(request('verified_email') === 'yes') selected @endif>@lang('title.yes')</option>
            </select>
        </div>
        <div class="col-12 col-md-3 mt-2">
            <label for="blocked">@lang('title.blocked')</label>
            <select class="form-control" name="blocked" id="blocked">
                <option value="">@lang('title.select')</option>
                <option value="no" @if(request('blocked') === 'no') selected @endif>@lang('title.no')</option>
                <option value="yes" @if(request('blocked') === 'yes') selected @endif>@lang('title.yes')</option>
            </select>
        </div>
    </x-admin.search-card>

    <x-admin.card
        title="{{ __('title.users') }}"
        canSeeButton="{{ auth()->user()->can('create', \App\Models\User::class) }}"
        buttonLink="{{ route('admin.users.create') }}">

        <x-slot name="buttonText">
            <i class="fa fa-plus"></i>
        </x-slot>

        <div class="table-responsive scrollbar">
            @if($users->isEmpty())
                <x-admin.empty-message></x-admin.empty-message>
            @else
                <table class="table table-hover table-striped overflow-hidden">
                    <thead>
                        <tr>
                            <th scope="col">@lang('title.id')</th>
                            <th scope="col">@lang('title.name')</th>
                            <th scope="col">@lang('title.email')</th>
                            <th scope="col">@lang('title.verified email')</th>
                            <th scope="col">@lang('title.mobile')</th>
                            <th scope="col">@lang('title.verified mobile')</th>
                            <th scope="col">@lang('title.registration date')</th>
                            <th scope="col">@lang('title.blocked')</th>
                            <th class="text-end" scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr class="align-middle">
                            <td class="text-nowrap">{{ $user->id }}</td>
                            <td class="text-nowrap">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-xl">
                                        <img src="{{ $user->avatar_path }}" alt="{{ $user->full_name }}" class="rounded-circle"></div>
                                    <div class="ms-2">{{ $user->full_name }}</div>
                                </div>
                            </td>
                            <td class="text-nowrap"><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                            <td class="text-nowrap">
                                @if($user->email_verified_at)
                                    <span class="badge rounded-pill d-block p-2 badge-soft-success">@lang('title.yes')</span>
                                @else
                                    <span class="badge rounded-pill d-block p-2 badge-soft-danger">@lang('title.no')</span>
                                @endif
                            </td>
                            <td class="text-nowrap"><a href="tel:{{ $user->mobile }}">{{ $user->mobile }}</a></td>
                            <td class="text-nowrap">
                                @if($user->mobile_verified_at)
                                    <span class="badge rounded-pill d-block p-2 badge-soft-success">@lang('title.yes')</span>
                                @else
                                    <span class="badge rounded-pill d-block p-2 badge-soft-danger">@lang('title.no')</span>
                                @endif
                            </td>
                            <td class="text-nowrap">{{ $user->persianCreatedAt('%d %B %Y') }}</td>
                            <td class="text-nowrap">
                                @if($user->isBlocked())
                                    <span class="badge rounded-pill d-block p-2 badge-soft-danger">@lang('title.yes')</span>
                                @else
                                    <span class="badge rounded-pill d-block p-2 badge-soft-success">@lang('title.no')</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @can('update', $user)
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn p-0" data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('title.edit')">
                                        <span class="text-500 fas fa-edit"></span>
                                    </a>
                                @endcan
                                @can('delete', $user)
                                    <delete-modal
                                        route="{{ route('admin.users.destroy', $user->id) }}"
                                        csrf="{{ csrf_token() }}"
                                        title="@lang('title.delete user')"
                                        text="@lang('text.delete user')"
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
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </x-admin.card>
@endsection
