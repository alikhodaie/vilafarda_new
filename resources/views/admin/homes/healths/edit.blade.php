@extends('layouts.admin.admin', ['title' => __('title.edit health'), 'active' => 'homes-healths'])

@section('content')
    <x-admin.card title="{{ __('title.edit health') }}">
        <form action="{{ route('admin.homes.healths.update', $health->id) }}" method="POST" class="p-3 row">
            @csrf
            @method('PUT')

            <div class="col-12 mb-5">
                <label class="form-label" for="title">@lang('title.title') <span>*</span></label>
                <input class="form-control" name="title" id="title" type="text" value="{{ old('title', $health->title) }}"/>
            </div>

            <div class="col-12 mt-5 d-flex justify-content-center">
                <button class="btn btn-falcon-success">@lang('title.edit')</button>
                <button type="reset" class="btn btn-falcon-warning mx-3">@lang('title.reset')</button>
                <a href="{{ route('admin.homes.healths.index') }}" class="btn btn-falcon-danger">@lang('title.return')</a>
            </div>
        </form>
    </x-admin.card>
@endsection
