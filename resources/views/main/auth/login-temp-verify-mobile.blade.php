@php use Illuminate\Support\Carbon; @endphp
@extends('layouts.main.main_mobile', ['title' => __('title.verify_code')])

@section('content')
    @include('layouts.main.partials.navbar-mobile')
    
    <!-- Page Header -->
    <div class="hero-image" style="background: #D39D1A; height: 150px; position: relative;">
        <div class="d-flex align-items-center justify-content-center h-100">
            <div class="text-center text-white px-3">
                <h1 class="fw-bold mb-2" style="font-size: 20px;">@lang('title.verify_code')</h1>
                <p class="mb-0" style="font-size: 14px; opacity: 0.9;">تایید کد ارسال شده</p>
            </div>
        </div>
    </div>

    <!-- Verify Form -->
    <div class="container px-3 py-4">
        <div class="bg-white rounded-3 p-4" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            
            <resend-timer
                csrf="{{ csrf_token() }}"
                mobile="{{ request('value') }}"
                now_prop="{{ now() }}"
                expired_at_prop="{{ Carbon::createFromTimestamp(request('expires')) }}"
                route_resend="{{ route('main.login.temp.send') }}"
                resend_text="دریافت کد جدید"
            ></resend-timer>

            <form method="POST" action="{{ request()->fullUrl() }}">
                @csrf

                <div class="mb-3">
                    <label for="code" class="form-label" style="font-size: 14px;">@lang('title.code')</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-shield-check text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0 text-center" 
                               placeholder="کد 5 رقمی" id="code" name="code" 
                               value="{{ old('code') }}" maxlength="5" style="font-size: 18px; letter-spacing: 2px;">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2" style="font-size: 14px; background: #D39D1A; border-color: #D39D1A;">
                    <i class="bi bi-check-circle me-2"></i>
                    @lang('title.login')
                </button>
            </form>
        </div>
    </div>
@endsection

