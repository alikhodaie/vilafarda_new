<?php

namespace App\Http\Requests\Dashboard\Home\Validate;

use Illuminate\Foundation\Http\FormRequest;

class StoreStep2Request extends FormRequest
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
            'latitude' => ['required', 'numeric', 'max:1000'],
            'longitude' => ['required', 'numeric', 'max:1000']
        ];
    }
}
