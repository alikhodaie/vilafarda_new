@extends('layouts.main.main_mobile', ['title' => __('title.register')])

@section('content')
    @include('layouts.main.partials.navbar-mobile')
    
    <!-- Page Header -->
    <div class="hero-image" style="background: #D39D1A; height: 150px; position: relative;">
        <div class="d-flex align-items-center justify-content-center h-100">
            <div class="text-center text-white px-3">
                <h1 class="fw-bold mb-2" style="font-size: 20px;">@lang('title.register')</h1>
                <p class="mb-0" style="font-size: 14px; opacity: 0.9;">ایجاد حساب کاربری جدید</p>
            </div>
        </div>
    </div>

    <!-- Register Form -->
    <div class="container px-3 py-4">
        <div class="bg-white rounded-3 p-4" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <x-auth.register>
                <x-slot name="show_login_button">
                    true
                </x-slot>
            </x-auth.register>
        </div>
    </div>
@endsection

