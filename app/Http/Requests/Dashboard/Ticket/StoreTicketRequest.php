<?php

namespace App\Http\Requests\Dashboard\Ticket;

use App\Models\TicketAttachment;
use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $max_size = TicketAttachment::MAX_SIZE;
        return [
            'title'         => ['required', 'string', 'max:255'],
            'message'       => ['required', 'string', 'max:1000'],
            'attachments'   => ['nullable', 'array', 'min:1', 'max:5'],
            'attachments.*' => ['nullable', 'file', "max:$max_size", 'mimes:jpg,jpeg,png,zip,rar'],
        ];
    }
}
