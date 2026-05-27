<div class="login-form">
    <form method="POST" action="{{ route('main.login') }}">
        @csrf

        <div class="form-group">
            <label for="mobile">@lang('title.mobile')</label>
            <div class="input-with-icon">
                <input type="text" class="form-control" id="mobile" name="mobile" value="{{ old('mobile') }}">
                <i class="ti-user"></i>
            </div>
        </div>

        <div class="form-group">
            <label for="password">@lang('title.password')</label>
            <div class="input-with-icon">
                <input type="password" class="form-control" name="password" id="password">
                <button
                    type="button"
                    class="password-toggle-btn"
                    id="toggle-password"
                    aria-label="نمایش یا مخفی کردن رمز عبور"
                    title="نمایش/مخفی کردن رمز عبور"
                >
                    <i class="ti-eye"></i>
                </button>
            </div>
        </div>

        <div class="form-group">
            <div class="eltio_ol9">
                <div class="eltio_k1">
                    <input id="remember_me" class="checkbox-custom" name="remember_me" type="checkbox">
                    <label for="remember_me" class="checkbox-custom-label">@lang('title.remember')</label>
                </div>
                <div class="eltio_k2">
                    <a href="{{ route('main.login.temp.send') }}">@lang('text.login_temp_code')</a>
                </div>
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-md full-width pop-login">@lang('title.login system')</button>
        </div>

        @if(isset($show_register_button) && $show_register_button)
            <div class="form-group">
                <a href="{{ route('main.register') }}" class="btn btn-md full-width rounded btn-warning">@lang('title.register')</a>
            </div>
        @endif

    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var toggleButton = document.getElementById('toggle-password');
        var passwordInput = document.getElementById('password');

        if (!toggleButton || !passwordInput) {
            return;
        }

        toggleButton.addEventListener('click', function () {
            passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
        });
    });
</script>
