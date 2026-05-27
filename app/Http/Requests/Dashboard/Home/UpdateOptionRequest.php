<?php

namespace App\Http\Requests\Dashboard\Home;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOptionRequest extends FormRequest
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
            'options' => ['nullable', 'exists:options,id'],
            'healths' => ['nullable', 'exists:healths,id'],
            'more_health' => ['nullable', 'string', 'max:250'],
            'safeties' => ['nullable', 'array'],
            'safeties.*.id' => ['nullable', 'exists:safeties,id'],
            'safeties.*.description' => ['nullable', 'string', 'max:250'],
            'more_safety' => ['nullable', 'string', 'max:250']
        ];
    }
}
