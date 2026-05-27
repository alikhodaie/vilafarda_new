@extends('layouts.admin.admin', ['title' => __('title.create comment'), 'active' => 'comments'])

@section('content')
    <x-admin.card title="{{ __('title.create comment') }}">
        <form action="{{ route('admin.comments.store') }}" method="POST" class="p-3 row">
            @csrf
            @method('POST')

            <div class="col-md-6 col-12 mb-3">
                <label for="user">@lang('title.user')</label>
                <user-input
                    @if(old('user'))
                        old="{{ old('user') }}"
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

            <div class="col-md-6 col-12 mb-3">
                <select-commentable
                    type_title="@lang('title.type')"
                    @if(old('home'))
                        old_article="{{ old('home') }}"
                    @endif
                    article_title="@lang('title.article')"
                    article_route="{{ route('admin.ajax.articles') }}"
                    @if(old('home'))
                        old_home="{{ old('home') }}"
                    @endif
                    home_title="@lang('title.home')"
                    home_route="{{ route('admin.ajax.homes') }}"
                    placeholder="@lang('title.select')"
                    select_label="@lang('title.select')"
                    selected_label="@lang('title.selected')"
                    deselect_label="@lang('title.remove')"
                    no_result_text="@lang('text.empty_result')"
                    no_options_text="@lang('text.empty_list')"
                ></select-commentable>
            </div>

            <div class="col-12 mb-3">
                <label class="form-label" for="comment">@lang('title.comment')</label>
                <textarea class="form-control" name="comment" placeholder="@lang('title.comment')">{!! old('comment') !!}</textarea>
            </div>

            <div class="col-12 col-md-6 mb-3">
                <label class="form-label" for="score">@lang('title.score')</label>
                <select class="form-control" name="score" id="score">
                    @for($i = 1; $i < 6; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>

            <div class="col-12 col-md-6 mb-3">
                <label class="form-label" for="status">@lang('title.status')</label>
                <select class="form-control" name="status" id="status">
                    @foreach(\App\Models\Comment::STATUES as $status)
                        <option value="{{ $status['value'] }}">{{ $status['fa_text'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 mt-5 d-flex justify-content-center">
                <button class="btn btn-falcon-success">@lang('title.submit')</button>
                <button type="reset" class="btn btn-falcon-warning mx-3">@lang('title.reset')</button>
                <a href="{{ route('admin.comments.index') }}" class="btn btn-falcon-danger">@lang('title.return')</a>
            </div>
        </form>
    </x-admin.card>

@endsection

