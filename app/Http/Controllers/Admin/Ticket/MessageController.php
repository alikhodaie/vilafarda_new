<?php

namespace App\Http\Controllers\Admin\Ticket;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Ticket\StoreTicketMessageRequest;
use App\Http\Requests\Admin\Ticket\UpdateTicketMessageRequest;
use App\Models\Ticket;
use App\Models\TicketAttachment;
use App\Models\TicketMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class MessageController extends Controller
{
    public function store(Ticket $ticket, StoreTicketMessageRequest $request)
    {
        try {
            DB::beginTransaction();

            $ticket->saveMessage($request, TicketMessage::ADMIN);

            $ticket->update(['status' => Ticket::ADMIN_ANSWERED]);

            DB::commit();
            return redirect()->route('admin.tickets.show', $ticket->id)->with('success', __('text.success.message_sent'));
        }
        catch (\Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('error', __('text.whoops'));
        }
    }

    public function edit(Ticket $ticket, TicketMessage $message)
    {
        $this->authorize('update', $message);

        return view('admin.tickets.messages.edit', compact('message'));
    }

    public function update(Ticket $ticket, TicketMessage $message, UpdateTicketMessageRequest $request)
    {
        try {
            DB::beginTransaction();

            // Delete removes attachments
            if ($request->filled('has-old-attachment')){
                $attachments = $message->attachments;

                if ($request->filled('old-attachments')){
                    foreach ($request->get('old-attachments') as $old_attach){
                        $attachments = $attachments->filter(function($item) use ($old_attach) {
                            return $item->id != $old_attach;
                        });
                    }
                }

                foreach($attachments as $attachment){
                    Storage::delete(TicketAttachment::FILE_PATH.$attachment->file);
                    $attachment->delete();
                }
            }

            // Add new Attachments
            if ($request->file('attachments')){
                $files = $request->file('attachments');

                foreach ($files as $file){
                    $message->saveAttachment($file);
                }
            }

            $message->update(['content' => $request->get('content')]);

            DB::commit();
            return redirect()->back()->with('success', __('text.success.message_update'));
        }
        catch (\Exception $e){
            DB::rollBack();
            Error::catch($e, __FUNCTION__, __CLASS__);
            return redirect()->back()->with('error', __('text.whoops'));
        }
    }

    public function destroy(Ticket $ticket, TicketMessage $message)
    {
        $this->authorize('delete', $message);

        try {
            DB::beginTransaction();

            foreach($message->attachments as $attachment) {
                Storage::delete(TicketAttachment::FILE_PATH.$attachment->file);

                $attachment->delete();
            }

            $message->delete();

            DB::commit();
            return redirect()->back()->with('success', __('text.success.message_delete'));
        }
        catch (\Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('error', __('text.whoops'));
        }

    }
}
