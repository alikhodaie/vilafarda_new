<?php

namespace App\Http\Controllers\Admin\Ticket;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Ticket\StoreTicketRequest;
use App\Http\Requests\Admin\Ticket\UpdateTicketRequest;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('index', Ticket::class);

        $tickets = Ticket::query()->whereHas('user', function ($query){
            $query->whereNull('deleted_at');

        })->search()->orderBy('status')->latest('updated_at')->paginate(10)->appends($request->all());

        return view('admin.tickets.index', compact(['tickets']));
    }

    public function show(Ticket $ticket)
    {
        $this->authorize('index', Ticket::class);

        return view('admin.tickets.show', compact(['ticket']));
    }

    public function create()
    {
        $this->authorize('create', Ticket::class);

        return view('admin.tickets.create');
    }

    public function store(StoreTicketRequest $request)
    {
        try {
            DB::beginTransaction();

            $ticket = User::query()->find($request->get('user'))->tickets()->create(['title' => $request->get('title'), 'status' => Ticket::ADMIN_ANSWERED]);
            $ticket->saveMessage($request, TicketMessage::ADMIN);

            DB::commit();
            return redirect()->route('admin.tickets.index', ['id' => $ticket->id])->with('success', __('text.success.create ticket', ['title' => $ticket->title]));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('error', __('text.whoops'));
        }
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $ticket->update(['status' => $request->status]);

        return redirect()->route('admin.tickets.index', ['id' => $ticket->id])->with('success', __('text.success.update ticket', ['title' => $ticket->title]));
    }

    public function destroy(Ticket $ticket)
    {
        $this->authorize('delete', $ticket);

        try {
            DB::beginTransaction();

            $ticket->delete();

            DB::commit();
            return redirect()->back()->with('success', __('text.success.delete ticket', ['title' => $ticket->title]));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('error', __('text.whoops'));
        }
    }
}
