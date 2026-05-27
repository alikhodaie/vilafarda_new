<?php

namespace App\Http\Requests\Admin\User;

use App\Models\User;
use App\Rules\MobileRule;
use App\Rules\PasswordRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', User::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "first_name" => ['required', 'string', 'max:250'],
            "last_name"  => ['required', 'string', 'max:250'],
            "mobile"     => ['required', new MobileRule(), 'unique:users,mobile'],
            "email"      => ['nullable', 'email', 'unique:users,email'],
            "avatar"     => ['nullable', 'image', 'max:'.User::MAX_AVATAR_SIZE],
            "password"   => ['required', new PasswordRule(), 'confirmed']
        ];
    }
}
