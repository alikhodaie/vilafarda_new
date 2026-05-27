<?php

namespace App\Http\Requests\Dashboard\Rent;

use Illuminate\Foundation\Http\FormRequest;

class RentDiscountRequest extends FormRequest
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
        return [
            'code' => ['required', 'string', 'min:1', 'max:50', 'exists:discounts,code'],
        ];
    }
}
