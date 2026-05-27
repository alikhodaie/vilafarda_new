<?php

namespace App\Http\Requests\Admin\Discount;

use App\Models\Discount;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDiscountRequest extends FormRequest
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
        $max = ($this->get('type') === Discount::PERCENT)
            ? 99
            : 100000000;

        $max_expired_date = (now()->lte($this->discount->expired_at))
            ? now()
            : $this->discount->expired_at;

        return [
            'expired_at' => ['required', 'date', 'after:'.$max_expired_date],
            'type' => ['required', Rule::in(collect(Discount::TYPES)->pluck('value'))],
            'amount' => ['required', 'numeric', 'min:1', "max:$max"],
        ];
    }
}
