<?php

namespace App\Http\Requests\Dashboard\Home;

use App\Models\Home;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePriceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->id == $this->home->user_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'week_price' => ['required', 'numeric', 'min:1000'],
            'wed_price' => ['required', 'numeric', 'min:1000'],
            'thu_price' => ['required', 'numeric', 'min:1000'],
            'fri_price' => ['required', 'numeric', 'min:1000'],
            'price_per_surplus' => ['required', 'numeric', 'min:0'],
            'off' => ['required', 'numeric', 'min:0', 'max:50'],
            'daily_off' => ['required', 'numeric', 'min:0', 'max:90'],
            'daily_off_amount' => ['required', Rule::in(array_keys(Home::DAILY_OFF_AMOUNTS))]
        ];
    }
}
