<?php

namespace App\Http\Requests\Dashboard\Home\Validate;

use Illuminate\Foundation\Http\FormRequest;

class StoreStep10Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->home->is_draft && $this->home->user_id == $this->user()->id;
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
            'price_per_surplus' => ['required', 'numeric', 'min:0']
        ];
    }
}
