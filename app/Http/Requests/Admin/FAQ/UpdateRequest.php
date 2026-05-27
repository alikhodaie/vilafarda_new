<?php

namespace App\Http\Requests\Admin\FAQ;

use App\Models\Category;
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
        return $this->user()->can('update', $this->faq);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'category' => ['required', Rule::exists('categories', 'id')->where('section', Category::FAQ)],
            'question' => ['required', 'string', 'max:250'],
            'answer' => ['required', 'string', 'max:5000'],
            'sort' => ['required', 'numeric', 'min:0']
        ];
    }
}
