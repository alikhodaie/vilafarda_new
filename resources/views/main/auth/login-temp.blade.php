@extends('layouts.main.main', ['title' => __('title.send_temp_code')])

@section('content')
    <div class="container">
        <div class="d-flex justify-content-center my-5">
            <div class="my-5">
                <div class="login-form">
                    <form method="POST" action="{{ route('main.login.temp.send') }}">
                        @csrf

                        <div class="form-group">
                            <label for="mobile">@lang('title.mobile')</label>
                            <div class="input-with-icon">
                                <input type="text" class="form-control" id="mobile" name="mobile" value="{{ old('mobile') }}">
                                <i class="ti-user"></i>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-md full-width pop-login">@lang('title.send_temp_code')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
