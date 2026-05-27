@extends('layouts.admin.admin', ['title' => __('title.edit_landing_page'), 'active' => 'landing-pages'])

@section('content')
    <x-admin.card title="{{ __('title.edit_landing_page') }}">
        <form action="{{ route('admin.landing-pages.update', $landingPage) }}" method="POST" class="p-3 row">
            @csrf
            @method('PUT')
            @include('admin.landing-pages.partials.form', ['landingPage' => $landingPage])

            <div class="col-12 mb-3">
                <div class="alert alert-light border small mb-0">
                    <strong>@lang('title.public_link'):</strong>
                    <a href="{{ $landingPage->url }}" target="_blank" rel="noopener" dir="ltr">{{ $landingPage->url }}</a>
                </div>
            </div>

            <div class="col-12 mt-3 d-flex justify-content-center">
                <button class="btn btn-falcon-success">@lang('title.submit')</button>
                <a href="{{ route('admin.landing-pages.index') }}" class="btn btn-falcon-danger mx-3">@lang('title.return')</a>
            </div>
        </form>
    </x-admin.card>
@endsection

@section('bottom-assets')
    @include('admin.landing-pages.partials.faq-script')
@endsection
