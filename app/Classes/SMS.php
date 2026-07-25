<?php

namespace App\Classes;

use App\Models\SmsLog;
use App\Models\User;
use App\Support\SmsTemplates;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Ippanel\Client;
use Throwable;

class SMS
{
    public static function sendPattern(string $mobile, string $pattern, array $parameters = [], array $context = []): bool
    {
        $user = isset($context['user_id'])
            ? User::query()->find($context['user_id'])
            : User::query()->where('mobile', $mobile)->first();

        $logData = [
            'user_id' => $user?->id,
            'mobile' => $mobile,
            'recipient_name' => $context['recipient_name'] ?? $user?->full_name,
            'pattern_id' => (string) $pattern,
            'pattern_title' => SmsTemplates::titleForPatternId($pattern),
            'parameters' => $parameters,
            'source' => $context['source'] ?? self::detectSource(),
        ];

        $related = $context['related'] ?? null;
        if ($related instanceof Model) {
            $logData['related_type'] = $related->getMorphClass();
            $logData['related_id'] = $related->getKey();
        }

        if (app()->isLocal()) {
            self::writeLog(array_merge($logData, [
                'status' => SmsLog::STATUS_SKIPPED,
                'response_body' => null,
                'error_message' => 'محیط local — ارسال واقعی انجام نشد',
            ]));

            return true;
        }

        $sender = config('sms.sender');
        $apiKey = config('ippanel.api_key');

        if (! $sender || ! $apiKey || ! $pattern) {
            self::writeLog(array_merge($logData, [
                'status' => SmsLog::STATUS_FAILED,
                'response_body' => null,
                'error_message' => 'تنظیمات IPPanel (API Key، شماره فرستنده یا کد پترن) ناقص است',
            ]));

            return false;
        }

        try {
            $client = app(Client::class);
            $response = $client->sendPattern(
                $pattern,
                $sender,
                self::normalizeMobile($mobile),
                self::toIppanelParams($parameters)
            );

            $success = $response->isSuccessful();

            self::writeLog(array_merge($logData, [
                'status' => $success ? SmsLog::STATUS_SENT : SmsLog::STATUS_FAILED,
                'response_body' => json_encode([
                    'data' => $response->getData(),
                    'meta' => $response->getMeta(),
                ], JSON_UNESCAPED_UNICODE),
                'error_message' => $success ? null : ($response->getMessage() ?? 'ارسال ناموفق'),
            ]));

            return $success;
        } catch (Throwable $e) {
            self::writeLog(array_merge($logData, [
                'status' => SmsLog::STATUS_FAILED,
                'response_body' => null,
                'error_message' => $e->getMessage(),
            ]));

            return false;
        }
    }

    public static function sendBulk(array $mobiles, string $message, array $context = []): bool
    {
        if ($mobiles === []) {
            return true;
        }

        if (app()->isLocal()) {
            return true;
        }

        $sender = config('sms.sender');
        $apiKey = config('ippanel.api_key');

        if (! $sender || ! $apiKey || trim($message) === '') {
            return false;
        }

        try {
            $client = app(Client::class);
            $recipients = array_map([self::class, 'normalizeMobile'], $mobiles);
            $response = $client->sendWebservice($message, $sender, $recipients);

            return $response->isSuccessful();
        } catch (Throwable) {
            return false;
        }
    }

    protected static function writeLog(array $data): void
    {
        try {
            SmsLog::query()->create($data);
        } catch (Throwable) {
            // لاگ نباید ارسال پیامک را مختل کند
        }
    }

    protected static function detectSource(): ?string
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 8);

        foreach ($trace as $frame) {
            $class = $frame['class'] ?? null;

            if ($class && $class !== self::class) {
                return $class.'::'.($frame['function'] ?? '');
            }
        }

        return null;
    }

    protected static function normalizeParameters(array $parameters): array
    {
        $max = (int) config('sms.parameter_max_length', 25);

        if ($max <= 0) {
            $max = 25;
        }

        $normalized = [];

        foreach ($parameters as $parameter) {
            if (! is_array($parameter)) {
                continue;
            }

            $name = trim((string) ($parameter['name'] ?? ''));

            if ($name === '') {
                continue;
            }

            $normalized[] = [
                'name' => $name,
                'value' => Str::limit(trim((string) ($parameter['value'] ?? '')), $max, ''),
            ];
        }

        return $normalized;
    }

    protected static function toIppanelParams(array $parameters): array
    {
        $params = [];

        foreach (self::normalizeParameters($parameters) as $parameter) {
            $params[$parameter['name']] = $parameter['value'];
        }

        return $params;
    }

    protected static function normalizeMobile(string $mobile): string
    {
        $digits = preg_replace('/\D+/', '', $mobile) ?? '';

        if ($digits === '') {
            return $mobile;
        }

        if (strpos($digits, '98') === 0) {
            return '+'.$digits;
        }

        if (strpos($digits, '0') === 0) {
            return '+98'.substr($digits, 1);
        }

        return '+98'.$digits;
    }
}
