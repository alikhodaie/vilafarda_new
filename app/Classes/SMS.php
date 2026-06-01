<?php

namespace App\Classes;

use App\Models\SmsLog;
use App\Models\User;
use App\Support\SmsTemplates;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Throwable;

class SMS
{
    const URL = 'https://api.sms.ir/v1/send/verify';

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

        $data = [
            'mobile' => $mobile,
            'templateId' => $pattern,
            'parameters' => $parameters,
        ];

        $header = [
            'Content-Type' => 'application/json',
            'Accept' => 'text/plain',
            'x-api-key' => config('sms.api-key'),
        ];

        try {
            $response = Http::withHeaders($header)
                ->timeout(15)
                ->post(self::URL, $data);

            $body = $response->json();
            $success = $response->successful() && (int) ($body['status'] ?? 0) === 1;

            self::writeLog(array_merge($logData, [
                'status' => $success ? SmsLog::STATUS_SENT : SmsLog::STATUS_FAILED,
                'response_body' => $response->body(),
                'error_message' => $success ? null : (string) ($body['message'] ?? 'ارسال ناموفق'),
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
}
