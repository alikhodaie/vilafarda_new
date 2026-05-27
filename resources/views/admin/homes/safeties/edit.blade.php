@extends('layouts.admin.admin', ['title' => __('title.edit safety'), 'active' => 'homes-safeties'])

@section('content')
    <x-admin.card title="{{ __('title.edit safety') }}">
        <form action="{{ route('admin.homes.safeties.update', $safety->id) }}" method="POST" class="p-3 row">
            @csrf
            @method('PUT')

            <div class="col-12 col-md-6 mb-5">
                <label class="form-label" for="title">@lang('title.title') <span>*</span></label>
                <input class="form-control" name="title" id="title" type="text" value="{{ old('title', $safety->title) }}"/>
            </div>

            <div class="col-12 col-md-6 mb-5">
                <label class="form-label" for="placeholder">@lang('title.place_holder')</label>
                <input class="form-control" name="placeholder" id="placeholder" type="text" value="{{ old('placeholder', $safety->placeholder) }}"/>
            </div>

            <div class="col-12 mt-5 d-flex justify-content-center">
                <button class="btn btn-falcon-success">@lang('title.edit')</button>
                <button type="reset" class="btn btn-falcon-warning mx-3">@lang('title.reset')</button>
                <a href="{{ route('admin.homes.safeties.index') }}" class="btn btn-falcon-danger">@lang('title.return')</a>
            </div>
        </form>
    </x-admin.card>
@endsection
