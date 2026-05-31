<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     */
    public function render($request, Throwable $e)
    {
        if ($e instanceof PostTooLargeException) {
            $uploadMax = ini_get('upload_max_filesize') ?: '?';
            $postMax = ini_get('post_max_size') ?: '?';
            $message = 'حجم ارسالی بیش از حد مجاز سرور است (خطای 413). '
                .'محدودیت PHP: upload_max_filesize='.$uploadMax.'، post_max_size='.$postMax.'. '
                .'اگر nginx دارید: client_max_body_size را در تنظیمات سایت (مثلاً 64M) بالا ببرید. '
                .'برای ویدئو بنر، فایل را کوتاه‌تر کنید یا ابتدا در مرورگر فشرده شود.';

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 413);
            }

            return redirect()->back(302, [], url('/'))->with('danger', $message);
        }

        return parent::render($request, $e);
    }
}
