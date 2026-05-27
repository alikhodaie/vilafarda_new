@extends('layouts.admin.admin', ['title' => __('title.create faq'), 'active' => 'faq'])

@section('content')
    <x-admin.card title="{{ __('title.create faq') }}">
        <form action="{{ route('admin.faq.store') }}" method="POST" class="p-3 row">
            @csrf

            <div class="col-12 mb-5">
                <label class="form-label" for="category">@lang('title.category')</label>
                <select name="category" id="category" class="form-control">
                    <option value="">@lang('title.select')</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @if($category->id == old('category')) selected @endif>{{ $category->title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 mb-5">
                <label class="form-label" for="question">@lang('title.question') <span>*</span></label>
                <input class="form-control" name="question" id="question" type="text" value="{{ old('question') }}"/>
            </div>

            <div class="col-12 mb-5">
                <label class="form-label" for="answer">@lang('title.answer') <span>*</span></label>
                <textarea class="form-control" name="answer" id="answer" type="text">{!! old('answer') !!}</textarea>
            </div>

            <div class="col-12 mb-5">
                <label class="form-label" for="sort">@lang('title.sort') <span>*</span></label>
                <input class="form-control" name="sort" id="sort" type="number" min="0" value="{{ old('sort', 0) }}"/>
            </div>

            <div class="col-12 mt-5 d-flex justify-content-center">
                <button class="btn btn-falcon-success">@lang('title.submit')</button>
                <button type="reset" class="btn btn-falcon-warning mx-3">@lang('title.reset')</button>
                <a href="{{ route('admin.faq.index') }}" class="btn btn-falcon-danger">@lang('title.return')</a>
            </div>
        </form>
    </x-admin.card>
@endsection
