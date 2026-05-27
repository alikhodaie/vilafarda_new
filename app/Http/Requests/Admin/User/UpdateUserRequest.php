<?php

namespace App\Http\Requests\Admin\User;

use App\Models\User;
use App\Rules\MobileRule;
use App\Rules\PasswordRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->user);
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
            "mobile"     => ['required', new MobileRule(), Rule::unique('users', 'mobile')->ignore($this->user)],
            "email"      => ['nullable', 'email', Rule::unique('users', 'email')->ignore($this->user)],
            "avatar"     => ['nullable', 'image', 'max:'.User::MAX_AVATAR_SIZE],
            "password"   => ['nullable', new PasswordRule(), 'confirmed']
        ];
    }
}
