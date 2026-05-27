<?php

namespace App\Http\Requests\Dashboard\Profile;

use App\Rules\MobileRule;
use App\Rules\PasswordRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'max:250'],
            'last_name'  => ['required', 'string', 'max:250'],
            'email'  => ['nullable', 'email', 'max:250', Rule::unique('users', 'email')->ignore($this->user())],
            'mobile' => ['required', new MobileRule(), Rule::unique('users', 'email')->ignore($this->user())],
            'password' => ['nullable', Rule::requiredIf(function (){ return request()->filled('old_password'); }), 'string', 'min:8', 'confirmed']
        ];
    }
}
