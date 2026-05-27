<?php

namespace App\Classes;

/*
 * ZarinPal API v4 Class
 * Updated to use new ZarinPal API v4
 */

class Zarinpal
{
	private function error_message($code)
	{
		$error = array(
			"-1" 	=> "اطلاعات ارسال شده ناقص است.",
			"-2" 	=> "IP و يا مرچنت كد پذيرنده صحيح نيست",
			"-3" 	=> "با توجه به محدوديت هاي شاپرك امكان پرداخت با رقم درخواست شده ميسر نمي باشد",
			"-4" 	=> "سطح تاييد پذيرنده پايين تر از سطح نقره اي است.",
			"-11" 	=> "درخواست مورد نظر يافت نشد.",
			"-12" 	=> "امكان ويرايش درخواست ميسر نمي باشد.",
			"-21" 	=> "هيچ نوع عمليات مالي براي اين تراكنش يافت نشد",
			"-22" 	=> "تراكنش نا موفق ميباشد",
			"-33" 	=> "رقم تراكنش با رقم پرداخت شده مطابقت ندارد",
			"-34" 	=> "سقف تقسيم تراكنش از لحاظ تعداد يا رقم عبور نموده است",
			"-40" 	=> "اجازه دسترسي به متد مربوطه وجود ندارد.",
			"-41" 	=> "اطلاعات ارسال شده مربوط به AdditionalData غيرمعتبر ميباشد.",
			"-42" 	=> "مدت زمان معتبر طول عمر شناسه پرداخت بايد بين 30 دقيه تا 45 روز مي باشد.",
			"-54" 	=> "درخواست مورد نظر آرشيو شده است",
			"100" 	=> "عمليات با موفقيت انجام گرديده است.",
			"101" 	=> "عمليات پرداخت موفق بوده و قبلا PaymentVerification تراكنش انجام شده است.",
		);

		if (array_key_exists("{$code}", $error))
		{
			return $error["{$code}"];
		} else {
			return "خطای نامشخص هنگام اتصال به درگاه زرین پال";
		}
	}

	public function redirect($url)
	{
		@header('Location: '. $url);
		echo "<meta http-equiv='refresh' content='0; url={$url}' />";
		echo "<script>window.location.href = '{$url}';</script>";
		exit;
	}

	public function request($MerchantID, $Amount, $Description="", $Email="", $Mobile="", $CallbackURL, $SandBox=false, $ZarinGate=false)
	{
		// تعیین URL بر اساس sandbox
		$apiUrl = $SandBox 
			? "https://sandbox.zarinpal.com/pg/v4/payment/request.json"
			: "https://api.zarinpal.com/pg/v4/payment/request.json";

		// آماده‌سازی داده‌ها
		$data = [
			"merchant_id" => $MerchantID,
			"amount" => (int)$Amount,
			"callback_url" => $CallbackURL,
			"description" => $Description ?: "پرداخت سفارش",
		];

		// اضافه کردن Email و Mobile اگر خالی نباشند
		if (!empty($Email)) {
			$data["metadata"]["email"] = $Email;
		}
		if (!empty($Mobile)) {
			$data["metadata"]["mobile"] = $Mobile;
		}

		// لاگ برای دیباگ
		\Log::info('Zarinpal API v4 Request', [
			'url' => $apiUrl,
			'data' => $data,
			'SandBox' => $SandBox,
		]);

		// ارسال درخواست با file_get_contents
		$context = stream_context_create([
			'http' => [
				'method'  => 'POST',
				'header'  => "Content-Type: application/json\r\n",
				'content' => json_encode($data, JSON_UNESCAPED_UNICODE),
				'timeout' => 30,
			]
		]);

		$result = @file_get_contents($apiUrl, false, $context);
		
		// اگر file_get_contents خطا داد، از CURL استفاده می‌کنیم
		if ($result === false) {
			$ch = curl_init($apiUrl);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_HTTPHEADER, [
				'Content-Type: application/json',
			]);
			$result = curl_exec($ch);
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$curlError = curl_error($ch);
			curl_close($ch);

			if ($curlError) {
				\Log::error('Zarinpal CURL Error', ['error' => $curlError]);
				$result = false;
			}
		}

		// لاگ برای دیباگ
		\Log::info('Zarinpal API v4 Response', [
			'rawResponse' => $result,
		]);

		if ($result === false) {
			return [
				"Status" => 0,
				"Message" => "خطا در اتصال به درگاه پرداخت",
				"StartPay" => "",
				"Authority" => ""
			];
		}

		$result = json_decode($result, true);

		if ($result === null || !is_array($result)) {
			return [
				"Status" => 0,
				"Message" => "پاسخ نامعتبر از سرور",
				"StartPay" => "",
				"Authority" => ""
			];
		}

		// بررسی ساختار پاسخ API v4
		$status = isset($result["data"]["code"]) ? (int)$result["data"]["code"] : 0;
		$authority = isset($result["data"]["authority"]) ? $result["data"]["authority"] : "";
		
		// اگر status در ریشه response بود (برای سازگاری با API قدیم)
		if ($status === 0 && isset($result["Status"])) {
			$status = (int)$result["Status"];
			$authority = isset($result["Authority"]) ? $result["Authority"] : "";
		}

		$message = $this->error_message($status);
		
		// ساخت URL پرداخت
		$startPay = "";
		if ($status == 100 && !empty($authority)) {
			$baseUrl = $SandBox 
				? "https://sandbox.zarinpal.com/pg/StartPay/"
				: "https://www.zarinpal.com/pg/StartPay/";
			
			$startPay = $baseUrl . $authority;
			
			if ($ZarinGate) {
				$startPay .= "/ZarinGate";
			}
		}

		return [
			"Status" => $status,
			"Message" => $message,
			"StartPay" => $startPay,
			"Authority" => $authority
		];
	}

	public function verify($MerchantID, $Amount, $SandBox=false, $ZarinGate=false)
	{
		$authority = isset($_GET['Authority']) && $_GET['Authority'] != "" ? $_GET['Authority'] : "";

		if (empty($authority)) {
			return [
				"Status" => 0,
				"Message" => "Authority یافت نشد",
				"RefID" => "",
				"Authority" => ""
			];
		}

		// تعیین URL بر اساس sandbox
		$apiUrl = $SandBox 
			? "https://sandbox.zarinpal.com/pg/v4/payment/verify.json"
			: "https://api.zarinpal.com/pg/v4/payment/verify.json";

		$data = [
			"merchant_id" => $MerchantID,
			"amount" => (int)$Amount,
			"authority" => $authority,
		];

		// لاگ برای دیباگ
		\Log::info('Zarinpal API v4 Verify Request', [
			'url' => $apiUrl,
			'data' => $data,
			'SandBox' => $SandBox,
		]);

		// ارسال درخواست با file_get_contents
		$context = stream_context_create([
			'http' => [
				'method'  => 'POST',
				'header'  => "Content-Type: application/json\r\n",
				'content' => json_encode($data, JSON_UNESCAPED_UNICODE),
				'timeout' => 30,
			]
		]);

		$result = @file_get_contents($apiUrl, false, $context);
		
		// اگر file_get_contents خطا داد، از CURL استفاده می‌کنیم
		if ($result === false) {
			$ch = curl_init($apiUrl);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_HTTPHEADER, [
				'Content-Type: application/json',
			]);
			$result = curl_exec($ch);
			$curlError = curl_error($ch);
			curl_close($ch);

			if ($curlError) {
				\Log::error('Zarinpal Verify CURL Error', ['error' => $curlError]);
				$result = false;
			}
		}

		// لاگ برای دیباگ
		\Log::info('Zarinpal API v4 Verify Response', [
			'rawResponse' => $result,
		]);

		if ($result === false) {
			return [
				"Status" => 0,
				"Message" => "خطا در اتصال به درگاه پرداخت",
				"RefID" => "",
				"Authority" => $authority
			];
		}

		$result = json_decode($result, true);

		if ($result === null || !is_array($result)) {
			return [
				"Status" => 0,
				"Message" => "پاسخ نامعتبر از سرور",
				"RefID" => "",
				"Authority" => $authority
			];
		}

		// بررسی ساختار پاسخ API v4
		$status = isset($result["data"]["code"]) ? (int)$result["data"]["code"] : 0;
		$refId = isset($result["data"]["ref_id"]) ? $result["data"]["ref_id"] : "";
		
		// اگر status در ریشه response بود (برای سازگاری با API قدیم)
		if ($status === 0 && isset($result["Status"])) {
			$status = (int)$result["Status"];
			$refId = isset($result["RefID"]) ? $result["RefID"] : "";
		}

		$message = $this->error_message($status);

		return [
			"Status" => $status,
			"Message" => $message,
			"RefID" => $refId,
			"Authority" => $authority
		];
	}
}
