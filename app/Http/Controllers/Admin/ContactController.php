<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    public function index()
    {
        $this->authorize('index', Contact::class);
        $contacts = Contact::query()->orderBy('is_seen')->latest()->paginate(10);

        return view('admin.contacts.index', compact('contacts'));
    }

    public function show(Contact $contact)
    {
        $this->authorize('index', Contact::class);

        $contact->seen();

        return view('admin.contacts.show', compact('contact'));
    }

    public function destroy(Contact $contact)
    {
        try {
            DB::beginTransaction();

            $contact->delete();

            DB::commit();
            return redirect()->back()->with('success', 'text.success.delete contact');
        }
        catch (\Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('contact', __('text.whoops'));
        }
    }
}
