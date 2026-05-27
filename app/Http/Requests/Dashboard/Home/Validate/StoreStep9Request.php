<?php

namespace App\Http\Requests\Dashboard\Home\Validate;

use App\Models\Health;
use Illuminate\Foundation\Http\FormRequest;

class StoreStep9Request extends FormRequest
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
        $healths = Health::getFromCache();

        return [
            'healths' => ['nullable', 'array', 'min:0', "max:{$healths->count()}"],
            'healths.*' => ['nullable', 'exists:healths,id'],
            'more' => ['nullable', 'string', 'max:250']
        ];
    }
}
