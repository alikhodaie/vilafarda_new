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
        Log::error($message);
    }

    public static function userMessage(Exception $exception): string
    {
        if (config('app.debug')) {
            return $exception->getMessage();
        }

        $message = $exception->getMessage();

        if (str_contains($message, "Column 'meta' cannot be null")) {
            return __('text.errors.article.meta_required');
        }

        if (str_contains($message, 'summary') && (
            str_contains($message, 'Incorrect string value') ||
            str_contains($message, 'Data too long for column')
        )) {
            return __('text.errors.article.summary_too_long');
        }

        if (str_contains($message, 'categorables') || (
            str_contains($message, 'Integrity constraint violation') &&
            str_contains($message, 'category')
        )) {
            return __('text.errors.article.category_invalid');
        }

        if (str_contains($message, 'tags') && str_contains($message, 'Integrity constraint violation')) {
            return __('text.errors.article.tag_invalid');
        }

        if (str_contains($message, 'image') || str_contains($message, 'Unable to write')) {
            return __('text.errors.article.image_upload');
        }

        if (str_contains($message, 'Duplicate entry') && str_contains($message, 'slug')) {
            return __('text.errors.article.slug_duplicate');
        }

        return __('text.whoops');
    }
}
