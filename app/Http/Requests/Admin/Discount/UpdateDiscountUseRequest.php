<?php

namespace App\Http\Requests\Admin\Discount;

use App\Models\Discount;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDiscountUseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->discount);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user' => ['required', Rule::exists('users_has_discounts', 'user_id')
                ->where('is_used', false)
                ->where('discount_id', $this->discount->id)
            ],
        ];
    }
}
