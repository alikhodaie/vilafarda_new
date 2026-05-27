<?php

namespace App\Http\Requests\Admin\Withdraw;

use App\Models\HostPayout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->withdraw);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'status' => ['required', Rule::in(array_keys(HostPayout::STATUSES))],
            'payment_reference' => ['nullable', 'string', 'max:100'],
        ];
    }
}
