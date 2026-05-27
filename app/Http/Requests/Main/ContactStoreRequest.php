<?php

namespace App\Http\Requests\Main;

use App\Rules\MobileRule;
use Illuminate\Foundation\Http\FormRequest;

class ContactStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
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
            'mobile' => ['required', 'string', new MobileRule()],
            'subject' => ['required', 'string', 'max:250'],
            'message' => ['required', 'string', 'max:500']
        ];
    }
}
