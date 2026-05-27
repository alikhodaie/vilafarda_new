<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class AuthController extends Controller
{
    /**
     * Check if phone number exists and send OTP
     */
    public function checkPhone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|regex:/^09\d{9}$/',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'شماره موبایل معتبر نیست'
            ], 422);
        }

        $phone = $request->phone;
        $ip = $request->ip();

        // Resend limit: allow only one resend per 10 minutes
        $resendKey = 'otp_resend_' . $phone;
        if (Cache::get($resendKey, 0) >= 1) {
            return response()->json([
                'success' => false,
                'message' => 'شما محدود شدید. لطفا بعدا دوباره تلاش کنید'
            ], 429);
        }
        // If this is a resend (not the first time), increment the resend count
        if (Cache::has('otp_' . $phone)) {
            Cache::increment($resendKey);
            Cache::put($resendKey, Cache::get($resendKey, 0), now()->addMinutes(10));
        } else {
            Cache::put($resendKey, 0, now()->addMinutes(10));
        }

        // Always generate a new OTP and overwrite previous
        $user = User::where('mobile', $phone)->first();
        $otp = str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
        Cache::put('otp_' . $phone, $otp, now()->addMinutes(5));
        Cache::put('otp_time_' . $phone, now(), now()->addMinutes(5));

        // Send SMS via sms.ir
        try {
            $client = new Client();
            $apiKey = 'LlXbcNiUXH4qBivDHxOF7QofNUpTouBpk8BeTq6xnr0r4daI0DvplaYDvd8SfaDj';
            $templateId = '120268';
            $smsBody = [
                'mobile' => $phone,
                'templateId' => $templateId,
                'parameters' => [
                    [ 'name' => 'code', 'value' => $otp ]
                ]
            ];
            $client->post('https://api.sms.ir/v1/send/verify', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'text/plain',
                    'x-api-key' => $apiKey,
                ],
                'body' => json_encode($smsBody),
                'timeout' => 10
            ]);
        } catch (\Exception $e) {
            Log::error('Error sending SMS: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'کد تایید ارسال شد',
            'user_exists' => $user ? true : false
        ]);
    }

    /**
     * Verify OTP and login user or prepare for registration
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|regex:/^09\d{9}$/',
            'otp' => 'required|string|size:5',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'اطلاعات وارد شده معتبر نیست'
            ], 422);
        }

        $phone = $request->phone;
        $otp = $request->otp;
        $ip = $request->ip();
        
        // Rate limiting for OTP verification
        $verifyRateLimitResult = $this->checkVerifyRateLimits($phone, $ip);
        if (!$verifyRateLimitResult['allowed']) {
            return response()->json([
                'success' => false,
                'message' => $verifyRateLimitResult['message'],
                'retry_after' => $verifyRateLimitResult['retry_after'] ?? null
            ], 429);
        }
        
        try {
            // Check if OTP exists and is not expired (5 minutes)
            $storedOtp = Cache::get('otp_' . $phone);
            $otpTime = Cache::get('otp_time_' . $phone);
         
            if (!$storedOtp || !$otpTime) {
                $this->incrementFailedAttempts($phone, $ip);
                return response()->json([
                    'success' => false,
                    'message' => 'کد تایید منقضی شده است یا وجود ندارد'
                ], 422);
            }

            // Check if OTP is expired (5 minutes)
            if (now()->diffInMinutes($otpTime) > 5) {
                // Clear expired OTP
                Cache::forget('otp_' . $phone);
                Cache::forget('otp_time_' . $phone);
                
                $this->incrementFailedAttempts($phone, $ip);
                return response()->json([
                    'success' => false,
                    'message' => 'کد تایید منقضی شده است'
                ], 422);
            }

            // Verify OTP
            if ($otp !== $storedOtp) {
                $this->incrementFailedAttempts($phone, $ip);
                return response()->json([
                    'success' => false,
                    'message' => 'کد تایید اشتباه است'
                ], 422);
            }

            // Find user
            $user = User::where('mobile', $phone)->first();
            
            // Clear OTP from cache
            Cache::forget('otp_' . $phone);
            Cache::forget('otp_time_' . $phone);

            if ($user) {
                // User exists - login directly
                $this->clearFailedAttempts($phone, $ip);
                // Generate a random password and set it for the user if not set (for OTP login)
                if (!$user->password) {
                    $randomPassword = Str::random(12);
                    $user->password = Hash::make($randomPassword);
                    $user->save();
                }
                // Attempt login using credentials
                $login = auth()->attempt([
                    'mobile' => $phone,
                    'password' => $request->get('password', 'default') // fallback for compatibility
                ], true);
                if (!$login) {
                    // If password is not available, force login
                    auth()->login($user, true);
                }

                Log::info("Successful login for {$phone} from IP {$ip}");

                return response()->json([
                    'success' => true,
                    'message' => 'ورود موفقیت‌آمیز بود',
                    'redirect' => '/dashboard',
                    'action' => 'login',
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->first_name . ' ' . $user->last_name,
                        'mobile' => $user->mobile
                    ]
                ]);
            } else {
                // User doesn't exist - prepare for registration
                // Store verified phone for registration
                Cache::put('verified_phone_' . $phone, true, now()->addMinutes(10));
                
                Log::info("OTP verified for new user registration: {$phone} from IP {$ip}");

                return response()->json([
                    'success' => true,
                    'message' => 'کد تایید صحیح است. لطفا رمز عبور خود را وارد کنید.',
                    'action' => 'register'
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Error in verifyOtp: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'خطا در تایید کد'
            ], 500);
        }
    }

    /**
     * Register new user with password
     */
    public function registerUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|regex:/^09\d{9}$/',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'اطلاعات وارد شده معتبر نیست'
            ], 422);
        }

        $phone = $request->phone;
        $password = $request->password;
        $ip = $request->ip();

        try {
            // Check if phone was verified
            if (!Cache::get('verified_phone_' . $phone)) {
                return response()->json([
                    'success' => false,
                    'message' => 'لطفا ابتدا شماره موبایل خود را تایید کنید'
                ], 422);
            }

            // Check if user already exists
            $existingUser = User::where('mobile', $phone)->first();
            if ($existingUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'کاربری با این شماره موبایل قبلاً ثبت شده است'
                ], 422);
            }

            // Create new user
            $user = User::create([
                'mobile' => $phone,
                'first_name' => 'کاربر',
                'last_name' => $phone,
                'password' => Hash::make($password),
                'email' => $phone . '@temp.com', // Temporary email
            ]);

            // Clear verification cache
            Cache::forget('verified_phone_' . $phone);

            // Login user
            $login = auth()->attempt([
                'mobile' => $phone,
                'password' => $password
            ], true);
            if (!$login) {
                auth()->login($user, true);
            }

            Log::info("New user registered: {$phone} from IP {$ip}");

            return response()->json([
                'success' => true,
                'message' => 'ثبت نام موفقیت‌آمیز بود',
                'redirect' => '/dashboard',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->first_name . ' ' . $user->last_name,
                    'mobile' => $user->mobile
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error in registerUser: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'خطا در ثبت نام' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Login existing user using phone and password
     */
    public function loginWithPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|regex:/^09\d{9}$/',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'اطلاعات وارد شده معتبر نیست'
            ], 422);
        }

        $phone = $request->phone;
        $password = $request->password;

        try {
            $user = User::where('mobile', $phone)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'کاربری با این شماره موبایل پیدا نشد'
                ], 404);
            }

            $login = auth()->attempt([
                'mobile' => $phone,
                'password' => $password
            ], true);

            if (!$login) {
                return response()->json([
                    'success' => false,
                    'message' => 'رمز عبور اشتباه است'
                ], 422);
            }

            return response()->json([
                'success' => true,
                'message' => 'ورود موفقیت‌آمیز بود',
                'redirect' => '/dashboard',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->first_name . ' ' . $user->last_name,
                    'mobile' => $user->mobile
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in loginWithPassword: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'خطا در ورود با رمز عبور'
            ], 500);
        }
    }

    /**
     * Check rate limits for OTP requests
     */
    private function checkRateLimits($phone, $ip)
    {
        // IP-based rate limiting: max 10 requests per hour
        $ipKey = 'otp_ip_' . $ip;
        $ipAttempts = Cache::get($ipKey, 0);
        
        if ($ipAttempts >= 10) {
            return [
                'allowed' => false,
                'message' => 'تعداد درخواست‌های شما از حد مجاز بیشتر شده است. لطفا یک ساعت دیگر تلاش کنید.',
                'retry_after' => 3600
            ];
        }

        // Phone-based rate limiting: max 3 requests per 15 minutes
        $phoneKey = 'otp_phone_' . $phone;
        $phoneAttempts = Cache::get($phoneKey, 0);
        
        if ($phoneAttempts >= 3) {
            $remainingTime = Cache::get($phoneKey . '_time', 0);
            $waitTime = max(0, 900 - (time() - $remainingTime));
            
            return [
                'allowed' => false,
                'message' => 'تعداد درخواست‌های کد تایید برای این شماره از حد مجاز بیشتر شده است. لطفا ' . ceil($waitTime / 60) . ' دقیقه دیگر تلاش کنید.',
                'retry_after' => $waitTime
            ];
        }

        return ['allowed' => true];
    }

    /**
     * Check rate limits for OTP verification
     */
    private function checkVerifyRateLimits($phone, $ip)
    {
        // IP-based verification rate limiting: max 20 attempts per hour
        $ipVerifyKey = 'verify_ip_' . $ip;
        $ipVerifyAttempts = Cache::get($ipVerifyKey, 0);
        
        if ($ipVerifyAttempts >= 20) {
            return [
                'allowed' => false,
                'message' => 'تعداد تلاش‌های شما از حد مجاز بیشتر شده است. لطفا یک ساعت دیگر تلاش کنید.',
                'retry_after' => 3600
            ];
        }

        // Phone-based verification rate limiting: max 5 attempts per 15 minutes
        $phoneVerifyKey = 'verify_phone_' . $phone;
        $phoneVerifyAttempts = Cache::get($phoneVerifyKey, 0);
        
        if ($phoneVerifyAttempts >= 5) {
            $remainingTime = Cache::get($phoneVerifyKey . '_time', 0);
            $waitTime = max(0, 900 - (time() - $remainingTime));
            
            return [
                'allowed' => false,
                'message' => 'تعداد تلاش‌های تایید کد برای این شماره از حد مجاز بیشتر شده است. لطفا ' . ceil($waitTime / 60) . ' دقیقه دیگر تلاش کنید.',
                'retry_after' => $waitTime
            ];
        }

        return ['allowed' => true];
    }

    /**
     * Increment failed attempts
     */
    private function incrementFailedAttempts($phone, $ip)
    {
        // Increment IP-based attempts
        $ipKey = 'verify_ip_' . $ip;
        $ipAttempts = Cache::get($ipKey, 0);
        Cache::put($ipKey, $ipAttempts + 1, now()->addHour());

        // Increment phone-based attempts
        $phoneKey = 'verify_phone_' . $phone;
        $phoneAttempts = Cache::get($phoneKey, 0);
        if ($phoneAttempts === 0) {
            Cache::put($phoneKey . '_time', time(), now()->addMinutes(15));
        }
        Cache::put($phoneKey, $phoneAttempts + 1, now()->addMinutes(15));

        // Increment OTP request attempts
        $otpIpKey = 'otp_ip_' . $ip;
        $otpIpAttempts = Cache::get($otpIpKey, 0);
        Cache::put($otpIpKey, $otpIpAttempts + 1, now()->addHour());

        $otpPhoneKey = 'otp_phone_' . $phone;
        $otpPhoneAttempts = Cache::get($otpPhoneKey, 0);
        if ($otpPhoneAttempts === 0) {
            Cache::put($otpPhoneKey . '_time', time(), now()->addMinutes(15));
        }
        Cache::put($otpPhoneKey, $otpPhoneAttempts + 1, now()->addMinutes(15));
    }

    /**
     * Clear failed attempts on successful login
     */
    private function clearFailedAttempts($phone, $ip)
    {
        Cache::forget('verify_phone_' . $phone);
        Cache::forget('verify_phone_' . $phone . '_time');
        Cache::forget('otp_phone_' . $phone);
        Cache::forget('otp_phone_' . $phone . '_time');
    }
} 