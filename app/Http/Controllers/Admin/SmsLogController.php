<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmsLog;
use Illuminate\Http\Request;

class SmsLogController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('index', SmsLog::class);

        $query = SmsLog::query()
            ->with('user')
            ->latest();

        if ($mobile = trim((string) $request->get('mobile'))) {
            $query->where('mobile', 'like', '%'.$mobile.'%');
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($patternId = trim((string) $request->get('pattern_id'))) {
            $query->where('pattern_id', $patternId);
        }

        if ($recipient = trim((string) $request->get('recipient'))) {
            $query->where(function ($builder) use ($recipient) {
                $builder->where('recipient_name', 'like', '%'.$recipient.'%')
                    ->orWhereHas('user', function ($userQuery) use ($recipient) {
                        $userQuery->whereRaw('CONCAT(first_name, " ", last_name) LIKE ?', ['%'.$recipient.'%']);
                    });
            });
        }

        $smsLogs = $query->paginate(20)->withQueryString();

        return view('admin.sms-logs.index', compact('smsLogs'));
    }

    public function show(SmsLog $smsLog)
    {
        $this->authorize('show', $smsLog);

        $smsLog->load(['user', 'related']);

        return view('admin.sms-logs.show', compact('smsLog'));
    }
}
