<?php

namespace App\Http\Requests\Admin\Admin;

use App\Models\User;
use App\Rules\MobileRule;
use App\Rules\PasswordRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('adminUpdate', $this->admin);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $validation = [
            "first_name" => ['required', 'string', 'max:250'],
            "last_name"  => ['required', 'string', 'max:250'],
            "mobile"     => ['required', new MobileRule(), Rule::unique('users', 'mobile')->ignore($this->admin)],
            "email"      => ['nullable', 'email', Rule::unique('users', 'email')->ignore($this->admin)],
            "image"      => ['nullable', 'image', 'max:'. User::MAX_AVATAR_SIZE],
            "password"   => ['nullable', new PasswordRule(), 'confirmed']
        ];

        if (auth()->user()->can('adminUpdateRole', $this->admin)){
            $validation['role'] = ['required', 'numeric', 'exists:roles,id'];
        }

        return $validation;
    }
}
