<?php

namespace App\Http\Requests\Dashboard\Ticket;

use App\Models\TicketAttachment;
use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->ticket->user_id === auth()->id();
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
            'message'       => ['required', 'string'],
            'attachments'   => ['nullable', 'array', 'min:1', 'max:5'],
            'attachments.*' => ['nullable', 'file', "max:$max_size", 'mimes:jpg,jpeg,png,zip,rar'],
        ];
    }
}
