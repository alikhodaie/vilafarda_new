<?php

namespace App\Http\Requests\Admin\Consultant;

use App\Models\Consultant;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->consultant);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:250'],
            'image' => ['nullable', 'image', 'max:'.Consultant::MAX_IMAGE_SIZE],
            'province' => ['required', 'exists:provinces,id'],
            'city' => ['required', 'exists:cities,id'],
            'phone_number' => ['nullable', 'string'],
            'whatsapp_number' => ['nullable', 'string'],
            'whatsapp_default_message' => ['nullable', 'string']
        ];
    }
}
