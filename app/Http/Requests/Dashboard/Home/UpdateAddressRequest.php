<?php

namespace App\Http\Requests\Dashboard\Home;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAddressRequest extends FormRequest
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
            'province' => ['required', 'exists:provinces,id'],
            'city' => ['required', Rule::exists('cities', 'id')->where('province_id', request()->get('province'))],
            'address' => ['required', 'string', 'max:500'],
            'latitude' => ['required', 'numeric', 'max:1000'],
            'longitude' => ['required', 'numeric', 'max:1000'],
        ];
    }
}
