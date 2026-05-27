class MobileLogin {
    constructor() {
        this.currentStep = 'phone'; // 'phone' or 'otp'
        this.phoneNumber = '';
        this.otpTimer = null;
        this.otpTimeLeft = 0;
        this.otpResent = false;
        this.init();
    }

    init() {
        this.bindEvents();
    }

    bindEvents() {
        // Phone input event
        document.getElementById('phone-input').addEventListener('input', (e) => {
            this.formatPhoneNumber(e.target);
        });

        // Login button event
        document.getElementById('login-btn').addEventListener('click', () => {
            this.handleLoginClick();
        });

        // OTP input events
        document.querySelectorAll('.otp-input').forEach((input, index) => {
            input.addEventListener('input', (e) => {
                this.handleOtpInput(e, index);
            });
            
            input.addEventListener('keydown', (e) => {
                this.handleOtpKeydown(e, index);
            });
        });

        // Verify OTP button
        document.getElementById('verify-btn').addEventListener('click', () => {
            this.verifyOtp();
        });

        // Register button
        document.getElementById('register-btn').addEventListener('click', () => {
            this.registerUser();
        });


        // Login with Password (from OTP step)
        document.getElementById('show-password-login').addEventListener('click', () => {
            this.showPasswordLoginStep();
        });

        // Password login button
        document.getElementById('password-login-btn').addEventListener('click', () => {
            this.loginWithPassword();
        });

        // Back to OTP from password login
        document.getElementById('back-to-otp').addEventListener('click', () => {
            this.showOtpStep();
        });

        // Resend OTP button
        document.getElementById('resend-otp-btn').addEventListener('click', () => {
            this.handleResendOtp();
        });

        this.bindPasswordToggle('password-login-input', 'toggle-password-login-input');
        this.bindPasswordToggle('password-input', 'toggle-password-input');
    }

    bindPasswordToggle(inputId, buttonId) {
        const input = document.getElementById(inputId);
        const button = document.getElementById(buttonId);

        if (!input || !button) {
            return;
        }

        button.addEventListener('click', () => {
            input.type = input.type === 'password' ? 'text' : 'password';
        });
    }

    formatPhoneNumber(input) {
        let value = input.value;
        // Convert Persian/Arabic digits to English
        value = value.replace(/[\u06F0-\u06F9]/g, d => String.fromCharCode(d.charCodeAt(0) - 0x06F0 + 48));
        value = value.replace(/[\u0660-\u0669]/g, d => String.fromCharCode(d.charCodeAt(0) - 0x0660 + 48));
        value = value.replace(/\D/g, '');
        // Remove country code if present
        if (value.startsWith('98')) {
            value = value.substring(2);
        }
        // Limit to 11 digits (0 + 10 digits)
        if (value.length > 11) {
            value = value.substring(0, 11);
        }
        input.value = value;
    }

    async handleLoginClick() {
        const phoneInput = document.getElementById('phone-input');
        const phoneNumber = phoneInput.value.trim();
        
        if (!this.validatePhoneNumber(phoneNumber)) {
            this.showError('لطفا شماره موبایل معتبر وارد کنید');
            return;
        }

        this.phoneNumber = phoneNumber;
        this.showLoading(true);
        
        try {
            const response = await fetch('/api/check-phone', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ phone: phoneNumber }),
                credentials: 'same-origin'
            });

            const data = await response.json();

            if (response.status === 429) {
                // Rate limiting error
                this.showError(data.message);
                if (data.retry_after) {
                    this.showRetryTimer(data.retry_after);
                }
            } else if (data.success) {
                this.showOtpStep();
            } else {
                this.showError(data.message || 'خطا در بررسی شماره موبایل');
            }
        } catch (error) {
            this.showError('خطا در ارتباط با سرور');
        } finally {
            this.showLoading(false);
        }
    }

    validatePhoneNumber(phone) {
        return /^09\d{9}$/.test(phone);
    }

    showOtpStep() {
        this.currentStep = 'otp';
        
        // Hide phone step
        document.getElementById('phone-step').style.display = 'none';
        
        // Show OTP step
        document.getElementById('otp-step').style.display = 'block';
        
        // Hide password login and register steps
        document.getElementById('password-login-step').style.display = 'none';
        document.getElementById('register-step').style.display = 'none';
        
        // Focus first OTP input (rightmost in RTL, but we want leftmost)
        setTimeout(() => {
            const otpInputs = document.querySelectorAll('.otp-input');
            // In RTL layout, the first input in DOM is visually on the right
            // So we need to focus the last input to get the leftmost visual position
            const leftmostInput = otpInputs[otpInputs.length - 1];
            if (leftmostInput) {
                leftmostInput.focus();
            }
        }, 100);
        
        // Update title
        document.getElementById('step-title').textContent = 'کد تایید';
        document.getElementById('step-subtitle').innerHTML = `کد تایید به شماره ${this.phoneNumber} ارسال شد`;
        
        // Clear all inputs
        document.getElementById('phone-input').value = '';
        document.getElementById('password-input').value = '';
        document.querySelectorAll('.otp-input').forEach(input => {
            input.value = '';
        });
        // OTP resend/timer logic
        this.otpResent = false;
        this.startOtpTimer(180); // 3 minutes
        document.getElementById('resend-otp-btn').style.display = 'none';
        // همیشه بعد از ارسال کد، امکان ورود با رمز را نشان بده (کاربر قدیمی استفاده می‌کند؛
        // اگر شماره جدید باشد، بک‌اند پیام مناسب برمی‌گرداند)
        const showPasswordLoginBtn = document.getElementById('show-password-login');
        if (showPasswordLoginBtn) {
            showPasswordLoginBtn.style.display = 'inline-block';
        }
    }

    showPhoneStep() {
        this.currentStep = 'phone';
        
        // Show phone step
        document.getElementById('phone-step').style.display = 'block';
        
        // Hide OTP and register steps
        document.getElementById('otp-step').style.display = 'none';
        document.getElementById('register-step').style.display = 'none';
        
        // Update title
        document.getElementById('step-title').textContent = 'ورود | ثبت نام';
        document.getElementById('step-subtitle').innerHTML = 'سلام<br>لطفا شماره موبایل خود را وارد کنید';
        
        // Clear all inputs
        document.getElementById('phone-input').value = '';
        document.getElementById('password-input').value = '';
        document.querySelectorAll('.otp-input').forEach(input => {
            input.value = '';
        });
    }

    handleOtpInput(event, index) {
        const input = event.target;
        let value = input.value;
        // Convert Persian/Arabic digits to English
        value = value.replace(/[\u06F0-\u06F9]/g, d => String.fromCharCode(d.charCodeAt(0) - 0x06F0 + 48));
        value = value.replace(/[\u0660-\u0669]/g, d => String.fromCharCode(d.charCodeAt(0) - 0x0660 + 48));
        // Only allow numbers
        if (!/^\d*$/.test(value)) {
            value = value.replace(/\D/g, '');
        }
        // Limit to one digit
        if (value.length > 1) {
            value = value.charAt(0);
        }
        input.value = value;
        // Move to next input (from left to right visually)
        if (value.length === 1) {
            const otpInputs = document.querySelectorAll('.otp-input');
            // In RTL, we need to go backwards in the array to move left visually
            const nextIndex = index - 1;
            if (nextIndex >= 0) {
                const nextInput = otpInputs[nextIndex];
                if (nextInput) {
                    nextInput.focus();
                }
            }
            // Check if all inputs are filled and auto-submit
            this.checkAndAutoSubmit();
        }
    }

    checkAndAutoSubmit() {
        const otpInputs = document.querySelectorAll('.otp-input');
        const allFilled = Array.from(otpInputs).every(input => input.value.length === 1);
        
        if (allFilled) {
            // Auto-submit after a short delay to let user see the last digit
            setTimeout(() => {
                this.verifyOtp();
            }, 300);
        }
    }

    handleOtpKeydown(event, index) {
        if (event.key === 'Backspace' && event.target.value === '' && index < 4) {
            const otpInputs = document.querySelectorAll('.otp-input');
            // In RTL, we need to go forwards in the array to move right visually
            const prevIndex = index + 1;
            if (prevIndex < otpInputs.length) {
                const prevInput = otpInputs[prevIndex];
                prevInput.focus();
            }
        }
    }

    async verifyOtp() {
        const otpInputs = document.querySelectorAll('.otp-input');
        // In RTL layout, we need to reverse the order to get the correct OTP
        const otp = Array.from(otpInputs).reverse().map(input => input.value).join('');
        
        if (otp.length !== 5) {
            this.showError('لطفا کد 5 رقمی را کامل وارد کنید');
            return;
        }

        this.showLoading(true);
        
        try {
            const response = await fetch('/api/verify-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ 
                    phone: this.phoneNumber,
                    otp: otp 
                }),
                credentials: 'same-origin'
            });

            const data = await response.json();
            
            if (response.status === 429) {
                // Rate limiting error
                this.showError(data.message);
                if (data.retry_after) {
                    this.showRetryTimer(data.retry_after);
                }
            } else if (data.success) {
                if (data.action === 'login') {
                    // User exists - login successful
                    this.showSuccess('ورود موفقیت‌آمیز بود');
                    setTimeout(() => {
                        window.location.href = data.redirect || '/dashboard';
                    }, 1000);
                } else if (data.action === 'register') {
                    // User doesn't exist - show registration step
                    this.showRegisterStep();
                }
            } else {
                this.showError(data.message || 'کد تایید اشتباه است');
                // Clear OTP inputs
                otpInputs.forEach(input => input.value = '');
                // Focus the leftmost input (last in DOM for RTL)
                otpInputs[otpInputs.length - 1].focus();
            }
        } catch (error) {
            this.showError('خطا در ارتباط با سرور');
        } finally {
            this.showLoading(false);
        }
    }

    showRegisterStep() {
        this.currentStep = 'register';
        
        // Hide OTP step
        document.getElementById('otp-step').style.display = 'none';
        
        // Show registration step
        document.getElementById('register-step').style.display = 'block';
        
        // Update title
        document.getElementById('step-title').textContent = 'ثبت نام';
        document.getElementById('step-subtitle').innerHTML = 'یک رمز برای اکانت خود تنظیم کنید';
        
        // Focus password input
        setTimeout(() => {
            document.getElementById('password-input').focus();
        }, 100);
    }

    async registerUser() {
        const passwordInput = document.getElementById('password-input');
        const password = passwordInput.value.trim();
        if (password.length < 6) {
            this.showError('رمز عبور باید حداقل 6 کاراکتر باشد');
            return;
        }
        this.showRegisterLoading(true);
        try {
            const response = await fetch('/api/register-user', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ 
                    phone: this.phoneNumber,
                    password: password 
                }),
                credentials: 'same-origin'
            });
            const data = await response.json();
            if (data.success) {
                this.showSuccess('ثبت نام موفقیت‌آمیز بود');
                setTimeout(() => {
                    window.location.href = data.redirect || '/dashboard';
                }, 1000);
            } else {
                this.showError(data.message || 'خطا در ثبت نام');
            }
        } catch (error) {
            this.showError('خطا در ارتباط با سرور');
        } finally {
            this.showRegisterLoading(false);
        }
    }

    showRegisterLoading(show) {
        const registerBtn = document.getElementById('register-btn');
        if (show) {
            registerBtn.disabled = true;
            registerBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>در حال ثبت نام...';
        } else {
            registerBtn.disabled = false;
            registerBtn.textContent = 'ثبت نام';
        }
    }

    showError(message) {
        const errorDiv = document.getElementById('error-message');
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
        
        setTimeout(() => {
            errorDiv.style.display = 'none';
        }, 5000);
    }

    showSuccess(message) {
        const successDiv = document.getElementById('success-message');
        successDiv.textContent = message;
        successDiv.style.display = 'block';
        
        setTimeout(() => {
            successDiv.style.display = 'none';
        }, 3000);
    }

    showLoading(show) {
        const loginBtn = document.getElementById('login-btn');
        const verifyBtn = document.getElementById('verify-btn');
        
        if (show) {
            loginBtn.disabled = true;
            verifyBtn.disabled = true;
            loginBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>در حال بررسی...';
            verifyBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>در حال بررسی...';
        } else {
            loginBtn.disabled = false;
            verifyBtn.disabled = false;
            loginBtn.textContent = 'ورود | ثبت نام';
            verifyBtn.textContent = 'تایید کد';
        }
    }

    showRetryTimer(seconds) {
        const errorDiv = document.getElementById('error-message');
        const minutes = Math.ceil(seconds / 60);
        const hours = Math.ceil(seconds / 3600);
        
        let timeText = '';
        if (hours > 1) {
            timeText = `${hours} ساعت`;
        } else if (minutes > 1) {
            timeText = `${minutes} دقیقه`;
        } else {
            timeText = `${seconds} ثانیه`;
        }
        
        errorDiv.innerHTML = `لطفا ${timeText} دیگر تلاش کنید.`;
        errorDiv.style.display = 'block';
    }

    showPasswordLoginStep() {
        this.currentStep = 'password-login';
        // Hide OTP step
        document.getElementById('otp-step').style.display = 'none';
        // Show password login step
        document.getElementById('password-login-step').style.display = 'block';
        // Update title
        document.getElementById('step-title').textContent = 'ورود با رمز عبور';
        document.getElementById('step-subtitle').innerHTML = `رمز عبور خود را وارد کنید برای شماره ${this.phoneNumber}`;
        // Focus password input
        setTimeout(() => {
            document.getElementById('password-login-input').focus();
        }, 100);
        // Clear password input
        document.getElementById('password-login-input').value = '';
    }

    async loginWithPassword() {
        const passwordInput = document.getElementById('password-login-input');
        const password = passwordInput.value.trim();
        if (password.length < 6) {
            this.showError('رمز عبور باید حداقل 6 کاراکتر باشد');
            return;
        }
        this.showLoading(true);
        try {
            const response = await fetch('/api/login-with-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    phone: this.phoneNumber,
                    password: password
                }),
                credentials: 'same-origin'
            });
            const data = await response.json();
            if (data.success) {
                this.showSuccess('ورود موفقیت‌آمیز بود');
                setTimeout(() => {
                    window.location.href = data.redirect || '/dashboard';
                }, 1000);
            } else {
                this.showError(data.message || 'خطا در ورود با رمز عبور');
            }
        } catch (error) {
            this.showError('خطا در ارتباط با سرور');
        } finally {
            this.showLoading(false);
        }
    }

    startOtpTimer(seconds) {
        this.otpTimeLeft = seconds;
        const timerEl = document.getElementById('otp-timer');
        const resendBtn = document.getElementById('resend-otp-btn');
        timerEl.style.display = 'inline';
        resendBtn.style.display = 'none';
        this.updateOtpTimerText();
        if (this.otpTimer) clearInterval(this.otpTimer);
        this.otpTimer = setInterval(() => {
            this.otpTimeLeft--;
            this.updateOtpTimerText();
            if (this.otpTimeLeft <= 0) {
                clearInterval(this.otpTimer);
                timerEl.style.display = 'none';
                if (!this.otpResent) resendBtn.style.display = 'inline';
            }
        }, 1000);
    }

    updateOtpTimerText() {
        const timerEl = document.getElementById('otp-timer');
        const min = String(Math.floor(this.otpTimeLeft / 60)).padStart(2, '0');
        const sec = String(this.otpTimeLeft % 60).padStart(2, '0');
        timerEl.textContent = `${min}:${sec} مانده تا دریافت کد مجدد`;
    }

    async handleResendOtp() {
        if (this.otpResent) return;
        this.otpResent = true;
        document.getElementById('resend-otp-btn').style.display = 'none';
        // Call check-phone again
        try {
            this.showLoading(true);
            const response = await fetch('/api/check-phone', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ phone: this.phoneNumber }),
                credentials: 'same-origin'
            });
            const data = await response.json();
            if (data.success) {
                // Reset OTP inputs and timer
                document.querySelectorAll('.otp-input').forEach(input => input.value = '');
                this.startOtpTimer(180);
                document.getElementById('otp-timer').style.display = 'none';
                document.getElementById('resend-otp-btn').style.display = 'none';
            } else {
                this.showError(data.message || 'خطا در ارسال مجدد کد');
            }
        } catch (error) {
            this.showError('خطا در ارتباط با سرور');
        } finally {
            this.showLoading(false);
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new MobileLogin();
}); 