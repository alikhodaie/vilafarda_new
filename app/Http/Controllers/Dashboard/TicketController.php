<?php

namespace App\Http\Controllers\Dashboard;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Ticket\StoreMessageRequest;
use App\Http\Requests\Dashboard\Ticket\StoreTicketRequest;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $tickets = auth()->user()->tickets()->search()->latest('updated_at')->paginate(10)->appends($request->all());

        if ($request->is_mobile ?? false) {
            return view('dashboard.tickets.index-mobile', compact(['tickets']));
        }

        return view('dashboard.tickets.index', compact(['tickets']));
    }

    public function show(Request $request, $ticket)
    {
        $ticket = auth()->user()->tickets()->where('id', $ticket)->with(['messages', 'messages.attachments'])->firstOrFail();

        if ($request->is_mobile ?? false) {
            return view('dashboard.tickets.show-mobile', compact(['ticket']));
        }

        return view('dashboard.tickets.show', compact(['ticket']));
    }

    public function create(Request $request)
    {
        if ($request->is_mobile ?? false) {
            return view('dashboard.tickets.create-mobile');
        }

        return view('dashboard.tickets.create');
    }

    public function store(StoreTicketRequest $request)
    {
        DB::beginTransaction();
        try {
            $ticket = auth()->user()->tickets()->create(['title' => $request->get('title')]);
            $ticket->saveMessage($request);

            DB::commit();
            return redirect()->route('dashboard.tickets.index')->with('success', __('text.success.submit_ticket'));
        }
        catch (\Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('warning', __('text.whoops'));
        }
    }

    public function reply(Ticket $ticket, StoreMessageRequest $request)
    {
        try {
            DB::beginTransaction();

            $ticket->saveMessage($request);
            $ticket->update(['status' => Ticket::USER_ANSWERED]);

            DB::commit();
            return redirect()->route('dashboard.tickets.show', $ticket->id)->with('success', __('text.success.message_sent'));
        }
        catch (\Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('warning', __('text.whoops'));
        }
    }

}
