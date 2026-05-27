<?php

namespace App\Http\Requests\Admin\Admin;

use App\Models\User;
use App\Rules\MobileRule;
use App\Rules\PasswordRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('adminCreate', User::class);
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
            "mobile"     => ['required', new MobileRule(), 'unique:users,mobile'],
            "email"      => ['nullable', 'email', 'unique:users,email'],
            "image"      => ['nullable', 'image', 'max:'. User::MAX_AVATAR_SIZE],
            "password"   => ['required', new PasswordRule(), 'confirmed']
        ];

        if (auth()->user()->can('adminAssignRole', User::class)){
            $validation['role'] = ['required', 'numeric', 'exists:roles,id'];
        }

        return $validation;
    }
}
