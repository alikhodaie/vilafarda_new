<div class="login-form">
    <form method="POST" action="{{ route('main.register') }}">
        @csrf

        <div class="form-group">
            <label for="first_name">@lang('title.first name')</label>
            <div class="input-with-icon">
                <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name') }}">
                <i class="ti-user"></i>
            </div>
        </div>

        <div class="form-group">
            <label for="last_name">@lang('title.last name')</label>
            <div class="input-with-icon">
                <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name') }}">
                <i class="ti-user"></i>
            </div>
        </div>

        <div class="form-group">
            <label for="mobile">@lang('title.mobile')</label>
            <div class="input-with-icon">
                <input type="text" class="form-control" id="mobile" name="mobile" value="{{ old('mobile') }}">
                <i class="ti-user"></i>
            </div>
        </div>

        <div class="form-group">
            <label for="email">@lang('title.email')</label>
            <div class="input-with-icon">
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                <i class="ti-user"></i>
            </div>
        </div>

        <div class="form-group">
            <label for="password">@lang('title.password')</label>
            <div class="input-with-icon">
                <input type="password" class="form-control" id="password" name="password">
                <i class="ti-unlock"></i>
            </div>
        </div>

        <div class="form-group">
            <label for="password_confirmation">@lang('title.password confirmation')</label>
            <div class="input-with-icon">
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                <i class="ti-unlock"></i>
            </div>
        </div>

        <div class="form-group">
            <div class="eltio_ol9">
                <div class="eltio_k1">
                    <input id="dds" class="checkbox-custom" name="dds" type="checkbox">
                    <label for="dds" class="checkbox-custom-label">با استفاده از وب سایت ، شرایط و ضوابط را می پذیرید</label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-md full-width pop-login">ثبت نام</button>
        </div>

        @if(isset($show_login_button) && $show_login_button)
            <div class="form-group">
                <a href="{{ route('main.login') }}" class="btn btn-md full-width rounded btn-warning">@lang('title.login')</a>
            </div>
        @endif

    </form>
</div>
