<?php

namespace App\Http\Requests\Admin\User;

use App\Models\User;
use App\Rules\MobileRule;
use Illuminate\Foundation\Http\FormRequest;

class IndexUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('index', User::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "id" => ['nullable', 'numeric'],
            "name" => ['nullable', 'string', 'max:250'],
            "email" => ['nullable', 'string', 'max:250'],
            "mobile" => ['nullable', new MobileRule()],
            "role" => ['nullable', 'numeric', 'exists:roles,id'],
            "verified_email" => ['nullable', 'string', 'in:yes,no'],
            "verified_mobile" => ['nullable', 'string', 'in:yes,no'],
            "blocked" => ['nullable', 'string', 'in:yes,no']
        ];
    }
}
