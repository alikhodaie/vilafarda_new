@php use App\Models\Comment; @endphp
@extends('layouts.admin.admin', ['title' => __('title.comments'), 'active' => 'comments'])

@section('content')
    <x-admin.search-card route="{{ route('admin.comments.index') }}">
        <div class="col-12 col-md-3 mt-2">
            <label for="id">@lang('title.id')</label>
            <input type="text" class="form-control" name="id" id="id" value="{{ request('id') }}">
        </div>
        <div class="col-12 col-md-3 mt-2">
            <label for="email">@lang('title.email')</label>
            <input type="email" class="form-control" name="email" id="email" value="{{ request('email') }}">
        </div>
        <div class="col-12 col-md-3 mt-2">
            <label for="comment">@lang('title.comment')</label>
            <input type="text" class="form-control" name="comment" id="comment" value="{{ request('comment') }}">
        </div>
        <div class="col-12 col-md-3 mt-2">
            <label for="status">@lang('title.status')</label>
            <select class="form-control" name="status" id="status">
                <option value="">@lang('title.select')</option>
                @foreach(Comment::STATUES as $status)
                    <option value="{{ $status['value'] }}"
                            @if($status['value'] == request('status')) selected @endif>{{ $status['fa_text'] }}</option>
                @endforeach
            </select>
        </div>
    </x-admin.search-card>
    <x-admin.card
        title="{{ __('title.comments') }}"
        canSeeButton="{{ auth()->user()->can('create', \App\Models\Comment::class) }}"
        buttonLink="{{ route('admin.comments.create') }}">

        <x-slot name="buttonText">
            <i class="fa fa-plus"></i>
        </x-slot>

        <div class="table-responsive scrollbar">
            @if($comments->isEmpty())
                <x-admin.empty-message></x-admin.empty-message>
            @else
                <table class="table table-hover table-striped overflow-hidden">
                    <thead>
                    <tr>
                        <th scope="col">@lang('title.id')</th>
                        <th scope="col">@lang('title.name')</th>
                        <th scope="col">@lang('title.user')</th>
                        <th scope="col">@lang('title.email')</th>
                        <th scope="col">@lang('title.type')</th>
                        <th scope="col">@lang('title.comment')</th>
                        <th scope="col">@lang('title.status')</th>
                        <th class="text-end" scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($comments as $comment)
                        <tr class="align-middle">
                            <td class="text-nowrap">{{ $comment->id }}</td>
                            <td class="text-nowrap">{{ $comment->full_name }}</td>
                            <td class="text-nowrap">{{ $comment->user_name }}</td>
                            <td class="text-nowrap"><a href="mailto:{{ $comment->email }}">{{ $comment->email }}</a>
                            </td>
                            <td class="text-nowrap"><a
                                    href="{{ $comment->commentable->getAdminRoute() }}">{{ $comment->commentable->getCommentType() }}</a>
                            </td>
                            <td class="text-nowrap">{{ $comment->limit_comment }}</td>
                            <td class="text-nowrap">
                                <span
                                    class="badge rounded-pill d-block p-2 badge-soft-{{ $comment->status('color') }}">{{ $comment->status() }}</span>
                            </td>
                            <td class="text-end">
                                @can('update', $comment)
                                    <a href="{{ route('admin.comments.edit', $comment->id) }}" class="btn p-0"
                                       data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('title.edit')">
                                        <span class="text-500 fas fa-edit"></span>
                                    </a>
                                @endcan
                                @can('destroy', $comment)
                                    <delete-modal
                                        route="{{ route('admin.comments.destroy', $comment->id) }}"
                                        csrf="{{ csrf_token() }}"
                                        title="@lang('title.delete comment')"
                                        text="@lang('text.delete comment')"
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
                    {{ $comments->links() }}
                </div>
            @endif
        </div>
    </x-admin.card>
@endsection
