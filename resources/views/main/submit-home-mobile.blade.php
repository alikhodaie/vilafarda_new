@extends('layouts.main.main_mobile', ['title' => setting('submit-home:page-title')])

@section('content')
    @include('layouts.main.partials.navbar-mobile')
    
    <!-- Page Header -->
    <div class="hero-image" style="background: #D39D1A; height: 200px; position: relative;">
        <div class="d-flex align-items-center justify-content-center h-100">
            <div class="text-center text-white px-3">
                <h1 class="fw-bold mb-2" style="font-size: 20px;">{{ setting('submit-home:title') }}</h1>
                <p class="mb-0" style="font-size: 14px; opacity: 0.9;">{{ setting('submit-home:page-title') }}</p>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="container px-3 py-4">
        <!-- First Card -->
        <div class="bg-white rounded-3 p-3 mb-3" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <h2 class="fw-bold mb-3" style="font-size: 18px; color: #333;">{{ setting('submit-home:first-title') }}</h2>
            <div style="max-height: 200px; overflow-y: auto;">
                <p style="font-size: 14px; line-height: 1.6; color: #666; margin: 0;">
                    {!! setting('submit-home:first-description') !!}
                </p>
            </div>
        </div>

        <!-- Second Card -->
        <div class="bg-white rounded-3 p-3 mb-4" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <h2 class="fw-bold mb-3" style="font-size: 18px; color: #333;">{{ setting('submit-home:second-title') }}</h2>
            <div style="max-height: 200px; overflow-y: auto;">
                <p style="font-size: 14px; line-height: 1.6; color: #666; margin: 0;">
                    {!! setting('submit-home:second-description') !!}
                </p>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="text-center">
            <a href="{{ route('dashboard.homes.create') }}" class="btn btn-success w-100 py-3" style="font-size: 16px; font-weight: bold; background: #D39D1A; border-color: #D39D1A;">
                <i class="bi bi-house-add me-2"></i>
                @lang('title.submit_home')
            </a>
        </div>
    </div>
@endsection



