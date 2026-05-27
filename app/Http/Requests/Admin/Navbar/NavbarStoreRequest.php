<?php

namespace App\Http\Requests\Admin\Navbar;

use App\Models\Navbar;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NavbarStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Navbar::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "title"  => ['required', 'string', 'max:100'],
            "link"   => ['required', 'url', 'max:300'],
            "parent" => ['nullable', 'numeric', Rule::exists('navbar', 'id')],
            "order"  => ['nullable', 'numeric', 'min:0']
        ];
    }
}
