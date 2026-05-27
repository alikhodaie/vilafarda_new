<?php

namespace App\Http\Controllers\Admin\Newsletter;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Newsletter\NewsletterRequest;
use App\Mail\NewsletterEmail;
use App\Models\Newsletter;
use App\Models\NewsletterSubscriber;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

class NewsletterController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('index', Newsletter::class);

        $newsletter = Newsletter::query()->latest()->paginate(10);
        return view('admin.newsletters.index', compact(['newsletter']));
    }

    public function show(Newsletter $newsletter)
    {
        $this->authorize('index', Newsletter::class);

        return view('admin.newsletters.show', compact(['newsletter']));
    }

    public function create()
    {
        $this->authorize('create', Newsletter::class);

        return view('admin.newsletters.create');
    }

    public function store(NewsletterRequest $request)
    {
        $this->authorize('create', Newsletter::class);


        try {
            DB::beginTransaction();

            Newsletter::query()->create([
                'title' => $request->get('title'),
                'body' => $request->get('body'),
            ]);

            $subscribers = NewsletterSubscriber::query()->pluck('email');
            Mail::to($subscribers)->send(new NewsletterEmail($request->get('title'), $request->get('body')));

            DB::commit();
            return redirect()->route('admin.newsletter.index')->with('success', __('text.success.create_newsletter'));
        } catch (Exception $exception) {
            DB::rollBack();
            Error::catch($exception, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function destroy(Newsletter $newsletter)
    {
        $this->authorize('delete', $newsletter);

        try {
            DB::beginTransaction();

            $newsletter->delete();

            DB::commit();
            return redirect()->back()->with('success', __('text.success.delete_newsletter'));

        } catch (Exception $exception) {
            DB::rollBack();
            Error::catch($exception, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }
}
