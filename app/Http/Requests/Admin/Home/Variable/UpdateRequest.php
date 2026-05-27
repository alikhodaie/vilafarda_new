<?php

namespace App\Http\Requests\Admin\Home\Variable;

use App\Models\Variable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->variable);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => ['required', 'string', 'max:250'],
            'placeholder' => ['required', 'string', 'max:250'],
            'type' => ['required', Rule::in(array_keys(Variable::TYPES))],
            'input_type' => ['required', Rule::in(array_keys(Variable::INPUT_TYPES))],
            'options' => ['nullable', Rule::requiredIf($this->checkRequireOption()), 'min:1'],
            'options.*.id' => ['nullable', 'numeric', Rule::exists('variable_options', 'id')->where('variable_id', $this->variable->id)],
            'options.*.name' => ['nullable', Rule::requiredIf($this->checkRequireOption()), 'string', 'max:250'],
        ];
    }

    public function checkRequireOption(): bool
    {
        return request()->get('input_type') === Variable::SELECT;
    }
}
