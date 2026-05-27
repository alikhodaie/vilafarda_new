@extends('layouts.admin.admin', ['title' => __('title.homes').' | '.__('title.update option'), 'active' => 'homes-options'])

@section('top-assets')
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/font/bootstrap-icons.min.css') }}">
    <style>
        .home-option-icon-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(2.75rem, 1fr));
            gap: 0.35rem;
            max-height: 16rem;
            overflow-y: auto;
        }
        .home-option-icon-grid__btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            aspect-ratio: 1;
            border: 1px solid var(--falcon-border-color, #d8e2ef);
            border-radius: 0.35rem;
            background: #fff;
            color: #344050;
            font-size: 1.25rem;
            padding: 0;
            cursor: pointer;
        }
        .home-option-icon-grid__btn:hover,
        .home-option-icon-grid__btn.is-selected {
            border-color: var(--falcon-primary, #2c7be5);
            background: rgba(44, 123, 229, 0.08);
            color: var(--falcon-primary, #2c7be5);
        }
    </style>
@endsection

@section('content')
    <x-admin.card title="{{ __('title.update option') }}">
        <form action="{{ route('admin.homes.options.update', $option->id) }}" enctype="multipart/form-data" method="POST" class="p-3 row">
            @csrf
            @method('PUT')

            <x-admin.home-option-icon-field :option="$option" />

            <div class="col-12 mb-5">
                <label class="form-label" for="title">@lang('title.title') <span>*</span></label>
                <input class="form-control" name="title" id="title" type="text" value="{{ old('title', $option->title) }}"/>
            </div>

            <div class="col-12 mt-5 d-flex justify-content-center">
                <button class="btn btn-falcon-success">@lang('title.submit')</button>
                <button type="reset" class="btn btn-falcon-warning mx-3">@lang('title.reset')</button>
                <a href="{{ route('admin.homes.options.index') }}" class="btn btn-falcon-danger">@lang('title.return')</a>
            </div>
        </form>
    </x-admin.card>
@endsection

@section('bottom-assets')
    <script src="{{ asset('assets/admin/js/home-option-icon-picker.js') }}"></script>
@endsection
