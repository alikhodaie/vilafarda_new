<?php

namespace App\Http\Requests\Admin\Navbar;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NavbarUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->navbar);
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
            "parent" => ['nullable', 'numeric', Rule::exists('navbar', 'id')->whereNot('id', $this->navbar->id)->whereNull('parent_id')],
            "order"  => ['nullable', 'numeric', 'min:0']
        ];
    }
}
