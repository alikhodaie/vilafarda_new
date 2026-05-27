@extends('layouts.main.main_mobile', ['title' => __('title.send_temp_code')])

@section('content')
    @include('layouts.main.partials.navbar-mobile')
    
    <!-- Page Header -->
    <div class="hero-image" style="background: #D39D1A; height: 150px; position: relative;">
        <div class="d-flex align-items-center justify-content-center h-100">
            <div class="text-center text-white px-3">
                <h1 class="fw-bold mb-2" style="font-size: 20px;">@lang('title.send_temp_code')</h1>
                <p class="mb-0" style="font-size: 14px; opacity: 0.9;">ورود با کد موقت</p>
            </div>
        </div>
    </div>

    <!-- Login Form -->
    <div class="container px-3 py-4">
        <div class="bg-white rounded-3 p-4" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <form method="POST" action="{{ route('main.login.temp.send') }}">
                @csrf

                <div class="mb-3">
                    <label for="mobile" class="form-label" style="font-size: 14px;">@lang('title.mobile')</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-phone text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0" id="mobile" name="mobile" 
                               value="{{ old('mobile') }}" placeholder="09123456789" style="font-size: 14px;">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2" style="font-size: 14px; background: #D39D1A; border-color: #D39D1A;">
                    <i class="bi bi-send me-2"></i>
                    @lang('title.send_temp_code')
                </button>
            </form>
        </div>
    </div>
@endsection

