@extends('layouts.admin.admin', ['title' => __('title.create_landing_page'), 'active' => 'landing-pages'])

@section('content')
    <x-admin.card title="{{ __('title.create_landing_page') }}">
        <form action="{{ route('admin.landing-pages.store') }}" method="POST" class="p-3 row">
            @csrf
            @include('admin.landing-pages.partials.form')
            <div class="col-12 mt-4 d-flex justify-content-center">
                <button class="btn btn-falcon-success">@lang('title.submit')</button>
                <a href="{{ route('admin.landing-pages.index') }}" class="btn btn-falcon-danger mx-3">@lang('title.return')</a>
            </div>
        </form>
    </x-admin.card>
@endsection

@section('bottom-assets')
    @include('admin.landing-pages.partials.faq-script')
@endsection
