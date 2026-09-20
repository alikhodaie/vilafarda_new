<?php

namespace App\Http\Controllers\Admin\Newsletter;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Newsletter\NewsletterRequest;
use App\Mail\NewsletterEmail;
use App\Models\Newsletter;
use App\Models\NewsletterSubscriber;
use App\Models\User;
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

        return view('admin.newsletters.create', [
            'audienceCounts' => $this->audienceCounts(),
        ]);
    }

    public function store(NewsletterRequest $request)
    {
        $this->authorize('create', Newsletter::class);


        $audience = (string) $request->get('audience', Newsletter::AUDIENCE_ALL);

        try {
            Newsletter::query()->create([
                'title' => $request->get('title'),
                'body' => $request->get('body'),
                'audience' => $audience,
            ]);
        } catch (Exception $exception) {
            Error::catch($exception, __CLASS__, __FUNCTION__);

            return redirect()->back()->withInput()->with('danger', __('text.whoops'));
        }

        $mailFailed = false;

        if ($audience === Newsletter::AUDIENCE_ALL) {
            $subscribers = NewsletterSubscriber::query()
                ->whereNotNull('email')
                ->where('email', '!=', '')
                ->pluck('email')
                ->filter()
                ->unique()
                ->values();

            if ($subscribers->isNotEmpty()) {
                try {
                    Mail::to($subscribers)->send(new NewsletterEmail($request->get('title'), $request->get('body')));
                } catch (Exception $exception) {
                    $mailFailed = true;
                    Error::catch($exception, __CLASS__, __FUNCTION__);
                }
            }
        }

        return redirect()
            ->route('admin.newsletter.index')
            ->with(
                $mailFailed ? 'warning' : 'success',
                $mailFailed
                    ? __('text.success.create_newsletter_mail_failed')
                    : __('text.success.create_newsletter')
            );
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

    /**
     * @return array<string, int>
     */
    private function audienceCounts(): array
    {
        return [
            Newsletter::AUDIENCE_ALL => User::query()->count(),
            Newsletter::AUDIENCE_HOSTS => User::query()
                ->whereHas('homes', function ($homes) {
                    $homes->where('is_draft', false);
                })
                ->count(),
            Newsletter::AUDIENCE_GUESTS => User::query()->whereHas('rents')->count(),
            Newsletter::AUDIENCE_ADMINS => User::query()->admin()->count(),
        ];
    }
}
