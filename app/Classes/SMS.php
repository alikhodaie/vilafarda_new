<?php


namespace App\Classes;


use Illuminate\Support\Facades\Http;

class SMS
{
    const URL = 'https://api.sms.ir/v1/send/verify';

    public static function sendPattern(string $mobile, string $pattern, array $parameters = [])
    {
        if (app()->isLocal()){
            return true;
        }

        $data = [
            "mobile" => $mobile,
            "templateId" => $pattern,
            "parameters" => $parameters
        ];

        $header = [
            "Content-Type" => "application/json",
            "Accept" => "text/plain",
            "x-api-key" => config("sms.api-key")
        ];

        Http::withHeaders($header)->post(self::URL, $data);
    }
}
