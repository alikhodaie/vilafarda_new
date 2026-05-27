<?php

namespace App\Http\Requests\Admin\Home\Date;

use Illuminate\Foundation\Http\FormRequest;

class DestroyDateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('deleteDate', $this->home);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date' => ['required', 'date_format:Y/m/d'],
        ];
    }
}
