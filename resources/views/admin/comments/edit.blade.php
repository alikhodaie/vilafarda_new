@extends('layouts.admin.admin', ['title' => __('title.edit comment'), 'active' => 'comments'])

@section('content')
    <x-admin.card title="{{ __('title.edit comment') }}">
        <form action="{{ route('admin.comments.update', $comment->id) }}" method="POST" class="p-3 row">
            @csrf
            @method('PUT')

            <div class="col-12 col-md-6 mb-3">
                <label class="form-label" for="name">@lang('title.name')</label>
                <input class="form-control" disabled id="name" type="text" value="{{ $comment->full_name }}" placeholder="@lang('title.name')"/>
            </div>

            <div class="col-12 col-md-6 mb-3">
                <label class="form-label" for="email">@lang('title.email')</label>
                <input class="form-control" disabled id="email" type="email" value="{{ $comment->email }}" placeholder="@lang('title.email')"/>
            </div>

            <div class="col-12 mb-3">
                <label class="form-label" for="comment">@lang('title.comment')</label>
                <textarea class="form-control" disabled id="comment" placeholder="@lang('title.comment')">{{ $comment->comment }}</textarea>
            </div>

            @include('admin.comments.partials.rating-breakdown', ['comment' => $comment])

            <div class="col-12 mb-3">
                <label class="form-label" for="status">@lang('title.status')</label>
                <select class="form-control" name="status" id="status">
                    @foreach(\App\Models\Comment::STATUES as $status)
                        <option value="{{ $status['value'] }}" @if($comment->status === $status['value']) selected @endif>{{ $status['fa_text'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 mt-5 d-flex justify-content-center">
                <button class="btn btn-falcon-success">@lang('title.edit')</button>
                <button type="reset" class="btn btn-falcon-warning mx-3">@lang('title.reset')</button>
                <a href="{{ route('admin.comments.index') }}" class="btn btn-falcon-danger">@lang('title.return')</a>
            </div>
        </form>
    </x-admin.card>

@endsection
