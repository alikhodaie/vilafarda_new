<?php

namespace App\Classes\Payment\Gateway;

use App\Classes\Error;
use App\Classes\Payment\GatewayInterface;
use App\Classes\Zarinpal as ZarinpalConnect;
use App\Models\Transaction;
use Exception;
use Illuminate\Validation\ValidationException;

class Zarinpal implements GatewayInterface
{
    private $transaction;
    private $call_back;

    private $merchant_id;
    private $is_sandbox;
    private $is_gate;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
        $this->call_back = route('main.call-back');
        $this->is_sandbox = (bool) config('zarinpal.sandbox');
        $this->is_gate = (bool) config('zarinpal.gate');
        
        // برای سندباکس، از merchant_id تست استفاده می‌کنیم
        if ($this->is_sandbox) {
            // Merchant ID تست زرین‌پال برای سندباکس
            $this->merchant_id = '71c705f8-bd37-11e6-aa0c-000c295eb8fc';
        } else {
            $this->merchant_id = config('zarinpal.merchant_id');
        }
        
        // لاگ برای دیباگ
        \Log::info('Zarinpal Gateway Constructor', [
            'is_sandbox' => $this->is_sandbox,
            'merchant_id' => $this->merchant_id,
            'config_sandbox' => config('zarinpal.sandbox'),
            'config_merchant_id' => config('zarinpal.merchant_id'),
        ]);
    }

    public function pay(): string
    {
        // بررسی merchant_id برای حالت غیر سندباکس
        if (!$this->is_sandbox && (empty($this->merchant_id) || $this->merchant_id === null)) {
            throw ValidationException::withMessages([
                'error' => 'Merchant ID تنظیم نشده است. لطفا از پنل مدیریت (تنظیمات > درگاه پرداخت) Merchant ID را وارد کنید.'
            ]);
        }

        // بررسی CallbackURL
        if (empty($this->call_back)) {
            throw ValidationException::withMessages([
                'error' => 'لینک بازگشت (Callback URL) تنظیم نشده است.'
            ]);
        }

        // استفاده از description تراکنش یا یک متن پیش‌فرض
        $description = 'پرداخت سفارش شماره ' . $this->transaction->id;
        
        // محدود کردن طول Description به 255 کاراکتر (حداکثر مجاز زرین‌پال)
        if (mb_strlen($description, 'UTF-8') > 255) {
            $description = mb_substr($description, 0, 255, 'UTF-8');
        }

        // دریافت ایمیل و موبایل کاربر
        $email = auth()->user()->email ?? '';
        $mobile = auth()->user()->mobile ?? '';

        // تبدیل مبلغ از تومان به ریال (زرین‌پال نیاز به مبلغ به ریال دارد)
        $amount = (int)($this->transaction->price * 10);
        
        // بررسی مبلغ
        if ($amount <= 0 || $amount < 1000) {
            throw ValidationException::withMessages([
                'error' => 'مبلغ تراکنش نامعتبر است. حداقل مبلغ 100 تومان (1000 ریال) می‌باشد.'
            ]);
        }

        // لاگ برای دیباگ
        \Log::info('Zarinpal Payment Request', [
            'merchant_id' => $this->merchant_id,
            'amount' => $amount,
            'description' => $description,
            'description_length' => mb_strlen($description, 'UTF-8'),
            'email' => $email,
            'mobile' => $mobile,
            'callback' => $this->call_back,
            'callback_length' => strlen($this->call_back),
            'sandbox' => $this->is_sandbox,
            'gate' => $this->is_gate,
            'transaction_id' => $this->transaction->id,
            'transaction_price' => $this->transaction->price,
        ]);

        $zarinpal = new ZarinpalConnect();

        $result = $zarinpal->request(
            $this->merchant_id,
            $amount,
            $description,
            $email,
            $mobile,
            $this->call_back,
            $this->is_sandbox,
            $this->is_gate
        );

        // لاگ نتیجه
        \Log::info('Zarinpal Payment Response', [
            'result' => $result,
            'status' => $result["Status"] ?? 'not_set',
            'message' => $result['Message'] ?? 'not_set',
        ]);

        if (isset($result["Status"]) && $result["Status"] == 100)
        {
            $this->transaction->update(['code' => $result["Authority"]]);

            return $result["StartPay"];
        }
        
        // نمایش خطای دقیق‌تر
        $errorMessage = isset($result['Message']) ? $result['Message'] : 'خطای نامشخص در اتصال به درگاه پرداخت';
        $status = isset($result["Status"]) ? $result["Status"] : 'نامشخص';
        
        Error::catch(new Exception("Status: {$status}, Message: {$errorMessage}, Result: " . json_encode($result)), __CLASS__, __METHOD__, 'مشکل درگاه پرداخت زرینپال');

        throw ValidationException::withMessages([
            'error' => $errorMessage . ' (کد خطا: ' . $status . ')'
        ]);
    }

    public function verify(): bool
    {
        // استفاده از merchant_id که در constructor تنظیم شده
        $merchant_id = $this->merchant_id;
        
        // تبدیل مبلغ از تومان به ریال (زرین‌پال نیاز به مبلغ به ریال دارد)
        $amount = $this->transaction->price * 10;
        
        $zarinpal = new ZarinpalConnect();
        $result = $zarinpal->verify(
            $merchant_id,
            $amount,
            $this->is_sandbox,
            $this->is_gate
        );

        if (isset($result["Status"]) && $result["Status"] == 100){
            $this->transaction->update(['reference' => $result["RefID"]]);
            return true;
        }

        return false;
    }
}
