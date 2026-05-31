@extends('layouts.main.main_mobile', ['title' => __('title.login')])

@section('content')
    <div class="d-flex flex-column bd-highlight mb-3 align-items-center min-vh-100 px-3 pt-5">
        <div class="p-2 bd-highlight">
            <img class="mb-5" src="{{ settingFilePath('app:logo') }}" alt="{{ config('app.name') }}" width="300" style="max-width: 100%; height: auto;">
        </div>
        
        <!-- Dynamic Title and Subtitle -->
        <div class="p-2 bd-highlight text-right w-100">
            <h1 id="step-title" class="fw-bold">ورود | ثبت نام</h1>
        </div>
        <div id="step-subtitle" class="p-1 bd-highlight text-right w-100 mt-3">
            سلام<br>
            لطفا شماره موبایل خود را وارد کنید
        </div>
        
        <!-- Error and Success Messages -->
        <div id="error-message" class="alert alert-danger w-100 mt-3" style="display: none;"></div>
        <div id="success-message" class="alert alert-success w-100 mt-3" style="display: none;"></div>
        
        <!-- Phone Step -->
        <div id="phone-step" class="w-100">
            <!-- Mobile Number Input -->
            <div class="w-100 mt-4">
                <input type="tel" 
                       id="phone-input"
                       class="form-control text-center py-3 custom-input" 
                       placeholder="مثال: 09190090067"
                       style="border-radius: 12px; border: 2px solid #e9ecef; font-size: 16px;">
            </div>
            
            <!-- Login Button -->
            <div class="w-100 mt-3">
                <button id="login-btn" class="btn w-100 py-3 text-white fw-bold" 
                        style="background-color: rgba(17, 17, 17, 0.89); border-radius: 12px; font-size: 16px;">
                    ورود | ثبت نام
                </button>
            </div>
            <!-- Terms Text: Only in phone step -->
            <div class="w-100 mt-2 text-center"> 
                <small class="text-muted" style="color: #6c757d !important; font-size: 12px; line-height: 1.4;">
                    ورود شما به معنای پذیرش قوانین سایت می باشد
                </small>
            </div>
        </div>
        
        <!-- OTP Step -->
        <div id="otp-step" class="w-100" style="display: none;">
            <!-- OTP Inputs -->
            <div class="w-100 mt-4 d-flex justify-content-center gap-2">
                <input type="text" class="otp-input form-control text-center" maxlength="1" style="width: 50px; height: 50px; border-radius: 8px; font-size: 18px; font-weight: bold;">
                <input type="text" class="otp-input form-control text-center" maxlength="1" style="width: 50px; height: 50px; border-radius: 8px; font-size: 18px; font-weight: bold;">
                <input type="text" class="otp-input form-control text-center" maxlength="1" style="width: 50px; height: 50px; border-radius: 8px; font-size: 18px; font-weight: bold;">
                <input type="text" class="otp-input form-control text-center" maxlength="1" style="width: 50px; height: 50px; border-radius: 8px; font-size: 18px; font-weight: bold;">
                <input type="text" class="otp-input form-control text-center" maxlength="1" style="width: 50px; height: 50px; border-radius: 8px; font-size: 18px; font-weight: bold;">
            </div>
            <!-- OTP Timer and Resend -->
            <div class="w-100 mt-3 text-center">
                <span id="otp-timer" style="font-size: 15px; color: #6c757d;"></span>
                <button id="resend-otp-btn" class="btn btn-link p-0" style="display: none; color: #007bff; font-size: 15px;">ارسال مجدد کد</button>
            </div>
            <!-- Verify Button -->
            <div class="w-100 mt-3">
                <button id="verify-btn" class="btn w-100 py-3 text-white fw-bold" 
                        style="background-color: rgba(17, 17, 17, 0.89); border-radius: 12px; font-size: 16px;">
                    تایید کد
                </button>
            </div>
            <!-- Login with Password Link -->
            <div class="w-100 mt-2 text-center">
                <button id="show-password-login" class="btn btn-link" style="color: #6c757d;text-decoration: none; font-size: 15px;">
                    ورود با رمز عبور
                </button>
            </div>
        </div>

        <!-- Password Login Step -->
        <div id="password-login-step" class="w-100" style="display: none;">
            <!-- Password Input -->
            <div class="w-100 mt-4">
                <div class="position-relative password-input-wrapper">
                    <input type="password" 
                           id="password-login-input"
                           class="form-control text-center py-3 custom-input" 
                           placeholder="رمز عبور خود را وارد کنید (حداقل 6 کاراکتر)"
                           style="border-radius: 12px; border: 2px solid #e9ecef; font-size: 16px;">
                    <button type="button" id="toggle-password-login-input" class="password-toggle-mobile-btn" aria-label="نمایش یا مخفی کردن رمز عبور">
                        <i class="ti-eye"></i>
                    </button>
                </div>
            </div>
            <!-- Login Button -->
            <div class="w-100 mt-3">
                <button id="password-login-btn" class="btn w-100 py-3 text-white fw-bold" 
                        style="background-color: rgba(17, 17, 17, 0.89); border-radius: 12px; font-size: 16px;">
                    ورود با رمز عبور
                </button>
            </div>
            <!-- Back to OTP Button -->
            <div class="w-100 mt-2 text-center">
                <button id="back-to-otp" class="btn btn-link text-muted" style="text-decoration: none;">
                    بازگشت به ورود با کد تایید
                </button>
            </div>
        </div>

        <!-- Registration Step -->
        <div id="register-step" class="w-100" style="display: none;">
            <!-- First Name Input -->
            <div class="w-100 mt-4">
                <input type="text"
                       id="first-name-input"
                       class="form-control text-center py-3 custom-input"
                       placeholder="نام"
                       style="border-radius: 12px; border: 2px solid #e9ecef; font-size: 16px;">
            </div>

            <!-- Last Name Input -->
            <div class="w-100 mt-3">
                <input type="text"
                       id="last-name-input"
                       class="form-control text-center py-3 custom-input"
                       placeholder="نام خانوادگی"
                       style="border-radius: 12px; border: 2px solid #e9ecef; font-size: 16px;">
            </div>

            <!-- Password Input -->
            <div class="w-100 mt-3">
                <div class="position-relative password-input-wrapper">
                    <input type="password" 
                           id="password-input"
                           class="form-control text-center py-3 custom-input" 
                           placeholder="حداقل ۶ کاراکتر"
                           style="border-radius: 12px; border: 2px solid #e9ecef; font-size: 16px;">
                    <button type="button" id="toggle-password-input" class="password-toggle-mobile-btn" aria-label="نمایش یا مخفی کردن رمز عبور">
                        <i class="ti-eye"></i>
                    </button>
                </div>
            </div>
            
            <!-- Register Button -->
            <div class="w-100 mt-3">
                <button id="register-btn" class="btn w-100 py-3 text-white fw-bold" 
                        style="background-color: rgba(17, 17, 17, 0.89); border-radius: 12px; font-size: 16px;">
                    ثبت نام
                </button>
            </div>
        </div>
    </div>

    <style>
        .custom-input:focus {
            border-color: rgba(17, 17, 17, 0.89) !important;
            box-shadow: 0 0 0 0.2rem rgba(17, 17, 17, 0.25) !important;
            outline: none !important;
        }
        
        .otp-input:focus {
            border-color: rgba(17, 17, 17, 0.89) !important;
            box-shadow: 0 0 0 0.2rem rgba(17, 17, 17, 0.25) !important;
            outline: none !important;
        }
        
        .otp-input {
            border: 2px solid #e9ecef;
        }

        .password-input-wrapper .custom-input {
            padding-right: 2.5rem !important;
        }

        .password-toggle-mobile-btn {
            position: absolute;
            right: 0.8rem;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: #6c757d;
            font-size: 1rem;
            line-height: 1;
            padding: 0;
            cursor: pointer;
        }
    </style>

    <!-- Include JavaScript -->
    <script src="{{ asset('js/mobile-login.js') }}"></script>
@endsection 