@extends('layouts.main.main_mobile', ['title' => setting('index:page-title')])

@section('content')
    @include('layouts.main.partials.navbar-mobile')
    @include('main.partials.search-box')
    @include('main.partials.slider')
    @include('main.partials.cities')

    @if($showOpenTomorrow ?? false)
        @include('main.partials.latest-villas')
    @endif

    @if($showOffHomes ?? false)
        @include('main.partials.last-minute-off', [
            'offCities' => $offCities ?? [],
            'offHomesInitial' => $offHomesInitial ?? collect(),
        ])
    @endif

    @include('main.partials.nearby-map-banner')

    @if($showSuggestions ?? false)
        @include('main.partials.suggestions', ['categories' => $suggestionCategories])
    @endif

    @if($showConsultants ?? false)
        @include('main.partials.consultant')
    @endif

    @if($showArticles ?? false)
        @include('main.partials.article')
    @endif

    @if($showComments ?? false)
        @include('main.partials.comments-section')
    @endif

    @include('layouts.main.partials.footer-mobile')
@endsection
