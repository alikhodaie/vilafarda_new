<?php

namespace App\Http\Controllers\Main;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255', 'unique:newsletter_subscribers,email']
        ]);

        try {
            DB::beginTransaction();

            NewsletterSubscriber::query()->create([
                'email' => $request->get('email'),
                'link'  => $request->server('HTTP_REFERER'),
                'hash'  => hash('sha256', Str::random()),
            ]);

            DB::commit();
            return response(__('text.success.submit_newsletter'));

        } catch (Exception $exception) {
            DB::rollBack();
            Error::catch($exception, __CLASS__, __FUNCTION__);
            throw ValidationException::withMessages([
                'error' => __('text.whoops')
            ]);
        }
    }
}
