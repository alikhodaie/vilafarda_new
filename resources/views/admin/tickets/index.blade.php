@extends('layouts.admin.admin', ['title' => __('title.tickets'), 'active' => 'tickets'])

@section('content')
    <x-admin.search-card route="{{ route('admin.tickets.index') }}">
        <div class="col-12 col-md-3 mt-2">
            <label for="id">@lang('title.id')</label>
            <input type="text" class="form-control" name="id" id="id" value="{{ request('id') }}">
        </div>
        <div class="col-12 col-md-3 mt-2">
            <label for="title">@lang('title.title')</label>
            <input type="text" class="form-control" name="title" id="title" value="{{ request('title') }}">
        </div>
        <div class="col-12 col-md-3 mt-2">
            <label for="status">@lang('title.status')</label>
            <select class="form-control" name="status" id="status">
                <option value="">@lang('title.select')</option>
                @foreach(\App\Models\Ticket::STATUS as $status)
                    <option value="{{ $status['value'] }}" @if($status['value'] === request('status')) selected @endif>{{ $status['fa_text_admin'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-3 mt-2">
            <label for="user">@lang('title.user')</label>
            <user-input
                @if(request('user'))
                   old="{{ request('user') }}"
                @endif
                route="{{ route('admin.ajax.users') }}"
                placeholder="@lang('text.select_user')"
                select_label="@lang('title.select')"
                selected_label="@lang('title.selected')"
                deselect_label="@lang('title.remove')"
                no_result_text="@lang('text.empty_result')"
                no_options_text="@lang('text.empty_list')"
            ></user-input>
        </div>
    </x-admin.search-card>

    <x-admin.card
        title="{{ __('title.tickets') }}"
        canSeeButton="{{ auth()->user()->can('create', \App\Models\Article::class) }}"
        buttonLink="{{ route('admin.tickets.create') }}">

        <x-slot name="buttonText">
            <i class="fa fa-plus"></i>
        </x-slot>

        <div class="table-responsive scrollbar">
            @if($tickets->isEmpty())
                <x-admin.empty-message></x-admin.empty-message>
            @else
                <table class="table table-hover table-striped overflow-hidden">
                    <thead>
                    <tr>
                        <th scope="col">@lang('title.id')</th>
                        <th scope="col">@lang('title.title')</th>
                        <th scope="col">@lang('title.user')</th>
                        <th scope="col">@lang('title.status')</th>
                        <th scope="col">@lang('title.created at')</th>
                        <th scope="col">@lang('title.updated_at')</th>
                        <th class="text-end" scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($tickets as $ticket)
                        <tr class="align-middle">
                            <td class="text-nowrap">{{ $ticket->id }}</td>
                            <td class="text-nowrap">{{ $ticket->title }}</td>
                            <td class="text-nowrap">{{ $ticket->user->full_name }}</td>
                            <td class="text-nowrap">
                                <span class="badge rounded-pill d-block p-2 badge-soft-{{ $ticket->status('color') }}">{{ $ticket->status('fa_text_admin') }}</span>
                            </td>
                            <td class="text-nowrap">{{ $ticket->persianCreatedAt() }}</td>
                            <td class="text-nowrap">{{ $ticket->persianUpdatedAt() }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="btn p-0" data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('title.show')">
                                    <span class="text-500 fas fa-eye"></span>
                                </a>
                                @can('delete', $ticket)
                                    <delete-modal
                                        route="{{ route('admin.tickets.destroy', $ticket->id) }}"
                                        csrf="{{ csrf_token() }}"
                                        title="@lang('title.delete ticket')"
                                        text="@lang('text.delete ticket')"
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
                    {{ $tickets->links() }}
                </div>
            @endif
        </div>

    </x-admin.card>
@endsection
