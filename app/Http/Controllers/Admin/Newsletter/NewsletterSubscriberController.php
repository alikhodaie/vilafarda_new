<?php

namespace App\Http\Controllers\Admin\Newsletter;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Newsletter\NewsletterSubscriberRequest;
use App\Models\NewsletterSubscriber;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class NewsletterSubscriberController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('index', NewsletterSubscriber::class);

        $subscribers = NewsletterSubscriber::query();

        if ($request->filled('id')){
            $subscribers->where('id', $request->get('id'));
        }
        if ($request->filled('email')){
            $subscribers->where('email', $request->get('email'));
        }

        $subscribers = $subscribers->paginate(10)->appends($request->all());
        return view('admin.newsletters.subscribers.index', compact(['subscribers']));
    }

    public function destroy(NewsletterSubscriber $subscriber)
    {
        $this->authorize('delete', $subscriber);

        try {
            DB::beginTransaction();

            $subscriber->delete();

            DB::commit();
            return redirect()->back()->with('success', __('text.success.delete_subscriber'));
        } catch (Exception $exception) {
            DB::rollBack();
            Error::catch($exception, __CLASS__, __FUNCTION__);
        }
    }
}
