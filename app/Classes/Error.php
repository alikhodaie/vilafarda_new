<?php


namespace App\Classes;


use Exception;
use Illuminate\Support\Facades\Log;

class Error
{
    public static function catch(Exception $exception, string $class = '', string $method = '', string $custom_message = '')
    {
        $message = 'Message: '. $exception->getMessage();
        $message .= PHP_EOL. 'Code: ' .$exception->getCode();
        $message .= PHP_EOL. 'Line: ' .$exception->getLine();
        if (!empty($class)){
            $message .= PHP_EOL. 'Class: ' .$class;
        }
        if (!empty($method)){
            $message .= PHP_EOL. 'Method: ' .$method;
        }
        if (!empty($custom_message)){
            $message .= PHP_EOL. 'With message: ' .$custom_message;
        }
        Log::debug($message);
    }
}
