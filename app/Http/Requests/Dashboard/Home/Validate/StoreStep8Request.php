<?php

namespace App\Http\Requests\Dashboard\Home\Validate;

use App\Models\Option;
use Illuminate\Foundation\Http\FormRequest;

class StoreStep8Request extends FormRequest
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
        $options = Option::getFromCache();

        return [
            'options' => ['nullable', 'array', 'min:0', "max:{$options->count()}"],
            'options.*' => ['nullable', 'exists:options,id']
        ];
    }
}
