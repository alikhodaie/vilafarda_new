<?php

namespace App\Classes\Payment;

use SoapClient;
use SoapFault;

class SizpayClient
{
    public const WSDL = 'https://rt.sizpay.ir/KimiaIPGRouteService.asmx?WSDL';

    public const PAYMENT_URL = 'https://rt.sizpay.ir/Route/Payment';

    public function __construct(
        private string $merchantId,
        private string $terminalId,
        private string $username,
        private string $password,
        private string $signData,
    ) {}

    public static function fromSettings(): self
    {
        return new self(
            trim((string) setting('sizpay:merchant-id', '')),
            trim((string) setting('sizpay:terminal-id', '')),
            trim((string) setting('sizpay:username', '')),
            trim((string) setting('sizpay:password', '')),
            trim((string) setting('sizpay:sign-data', '')),
        );
    }

    public static function isExtensionAvailable(): bool
    {
        return extension_loaded('soap') && class_exists(SoapClient::class);
    }

    /**
     * @return array{merchant_id: string, terminal_id: string, username: string, password: string, sign_data: string}
     */
    public function credentials(): array
    {
        return [
            'merchant_id' => $this->merchantId,
            'terminal_id' => $this->terminalId,
            'username' => $this->username,
            'password' => $this->password,
            'sign_data' => $this->signData,
        ];
    }

    public function isConfigured(): bool
    {
        foreach ($this->credentials() as $value) {
            if ($value === '') {
                return false;
            }
        }

        return self::isExtensionAvailable();
    }

    /**
     * @return string|null Token on success
     */
    public function getToken(int $amountRial, int $orderId, string $returnUrl, string $extraInf = ''): ?string
    {
        try {
            $client = new SoapClient(self::WSDL, [
                'connection_timeout' => 30,
                'cache_wsdl' => WSDL_CACHE_NONE,
            ]);

            $response = $client->GetToken2([
                'MerchantID' => $this->merchantId,
                'TerminalID' => $this->terminalId,
                'UserName' => $this->username,
                'Password' => $this->password,
                'Amount' => $amountRial,
                'OrderID' => $orderId,
                'ReturnURL' => $returnUrl,
                'InvoiceNo' => $orderId,
                'DocDate' => '',
                'ExtraInf' => $extraInf,
                'AppExtraInf' => '',
                'SignData' => $this->signData,
            ]);

            $result = json_decode($response->GetToken2Result ?? '', true);

            if (isset($result['ResCod']) && in_array((string) $result['ResCod'], ['0', '00'], true)) {
                return $result['Token'] ?? null;
            }

            $apiMessage = self::formatApiError($result);

            \Log::warning('Sizpay GetToken2 failed', [
                'result' => $result,
                'order_id' => $orderId,
                'return_url' => $returnUrl,
                'amount_rial' => $amountRial,
                'message' => $apiMessage,
            ]);

            throw new \RuntimeException($apiMessage);
        } catch (SoapFault $e) {
            \Log::error('Sizpay GetToken2 SOAP error', [
                'message' => $e->getMessage(),
                'order_id' => $orderId,
                'return_url' => $returnUrl,
            ]);

            throw new \RuntimeException('خطا در اتصال SOAP به سیزپی: '.$e->getMessage(), 0, $e);
        }
    }

    public static function formatApiError(?array $result): string
    {
        if ($result === null || $result === []) {
            return 'پاسخ خالی از درگاه سیزپی';
        }

        $code = $result['ResCod'] ?? $result['resCod'] ?? $result['Code'] ?? 'نامشخص';
        $message = $result['ResMsg'] ?? $result['resMsg'] ?? $result['Message'] ?? $result['message'] ?? null;

        if ($message) {
            return trim((string) $message).' (کد: '.$code.')';
        }

        return 'خطا از درگاه سیزپی (کد: '.$code.')';
    }

    public function confirm(string $token): bool
    {
        try {
            $client = new SoapClient(self::WSDL, [
                'connection_timeout' => 30,
                'cache_wsdl' => WSDL_CACHE_NONE,
            ]);

            $response = $client->Confirm2([
                'MerchantID' => $this->merchantId,
                'TerminalID' => $this->terminalId,
                'UserName' => $this->username,
                'Password' => $this->password,
                'Token' => $token,
                'SignData' => '',
            ]);

            $result = json_decode($response->Confirm2Result ?? '', true);

            return isset($result['ResCod']) && in_array((string) $result['ResCod'], ['0', '00'], true);
        } catch (SoapFault $e) {
            \Log::error('Sizpay Confirm2 SOAP error', ['message' => $e->getMessage(), 'token' => $token]);

            return false;
        }
    }
}
