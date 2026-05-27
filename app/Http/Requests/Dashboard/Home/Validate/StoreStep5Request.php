<?php

namespace App\Http\Requests\Dashboard\Home\Validate;

use App\Models\Home;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStep5Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->home->is_draft && $this->home->user_id == $this->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'atmosphere' => ['required', Rule::in(array_keys(Home::ATMOSPHERES))],
            'type' => ['required', Rule::in(array_keys(Home::TYPES))],
            'area' => ['required', Rule::in(array_keys(Home::AREAS))]
        ];
    }
}
