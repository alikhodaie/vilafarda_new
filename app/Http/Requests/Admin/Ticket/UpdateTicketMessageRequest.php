<?php

namespace App\Http\Requests\Admin\Ticket;

use App\Models\TicketAttachment;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', request()->message);
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
            'content'            => ['required', 'string'],
            'has-old-attachment' => ['nullable'],
            'old-attachments'    => ['nullable', 'array'],
            'old-attachments.*'  => ['nullable', 'numeric', 'exists:ticket_attachments,id'],
            'attachments'        => ['nullable', 'array', 'min:1', 'max:5'],
            'attachments.*'      => ['nullable', 'file', "max:$max_size", 'mimes:jpg,jpeg,png,zip,rar'],
        ];
    }
}
