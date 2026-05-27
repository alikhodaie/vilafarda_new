@php use Illuminate\Support\Carbon; @endphp
@extends('layouts.main.main', ['title' => __('title.verify_code')])

@section('content')
    <div class="container">
        <div class="d-flex justify-content-center my-5">
            <div class="my-5">
                <div class="login-form">

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

                        <div class="form-group">
                            <div class="input-with-icon">
                                <input type="text" class="form-control" placeholder="@lang('title.code')" id="code"
                                       name="code" value="{{ old('code') }}">
                                <i class="ti-user"></i>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-md full-width pop-login">@lang('title.login')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
