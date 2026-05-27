<?php

namespace App\Http\Requests\Dashboard\Home\Validate;

use Illuminate\Foundation\Http\FormRequest;

class StoreStep6Request extends FormRequest
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
            'main_guest' => ['required', 'numeric', 'min:1'],
            'extra_guest' => ['required', 'numeric', 'min:0'],
            'yard' => ['required', 'numeric', 'min:1'],
            'infrastructure' => ['required', 'numeric', 'min:1'],
        ];
    }
}
