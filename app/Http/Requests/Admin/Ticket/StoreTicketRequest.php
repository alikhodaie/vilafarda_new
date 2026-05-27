<?php

namespace App\Http\Requests\Admin\Ticket;

use App\Models\Ticket;
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
        return $this->user()->can('create', Ticket::class);
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
            'user'          => ['required', 'numeric', 'exists:users,id'],
            'title'         => ['required', 'string', 'max:255'],
            'message'       => ['required', 'string'],
            'attachments'   => ['nullable', 'array', 'min:1', 'max:5'],
            'attachments.*' => ['nullable', 'file', "max:$max_size", 'mimes:jpg,jpeg,png,zip,rar'],
        ];
    }
}
