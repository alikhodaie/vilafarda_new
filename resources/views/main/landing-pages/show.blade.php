@extends('layouts.main.main', ['title' => $landingPage->title, 'landingPage' => $landingPage, 'homes' => $homes])

@section('content')
    @include('main.landing-pages.partials.hero')

    <section class="gray pt-4 pb-5">
        <div class="container">
            <div class="sec-heading center mb-4">
                <h2 class="h4 mb-1">@lang('title.landing_homes_list')</h2>
                @if($landingPage->city)
                    <p class="text-muted mb-0">
                        {{ persianNumber($homes->total()) }} @lang('title.residence')
                        @if($landingPage->homeTypeLabel())
                            — {{ $landingPage->homeTypeLabel() }}
                        @endif
                        @if($landingPage->city->name)
                            — {{ $landingPage->city->name }}
                        @endif
                    </p>
                @endif
            </div>

            <div class="row">
                @forelse($homes as $home)
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                        @include('main.homes.partials.new-home-card', ['home' => $home, 'is_today' => $isTodayPrice])
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-warning text-center">@lang('text.empty search')</div>
                    </div>
                @endforelse
            </div>

            @if($homes->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $homes->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
