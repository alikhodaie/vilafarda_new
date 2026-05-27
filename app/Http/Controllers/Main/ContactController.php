<?php

namespace App\Http\Controllers\Main;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Main\ContactStoreRequest;
use App\Models\Contact;
use Exception;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    public function store(ContactStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            Contact::query()->create([
                'name' => $request->get('name'),
                'mobile' => $request->get('mobile'),
                'subject' => $request->get('subject'),
                'message' => $request->get('message'),
            ]);

            DB::commit();
            return redirect()->back()->with('success', __('text.success.request_contact'));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }
}
