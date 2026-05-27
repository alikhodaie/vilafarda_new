<?php

namespace App\Http\Requests\Dashboard\Home\Validate;

use App\Models\Variable;
use Illuminate\Foundation\Http\FormRequest;

class StoreStep13Request extends FormRequest
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
        $validation = [
            'rules' => ['nullable', 'string', 'max:500']
        ];

        $validation += Variable::validation();

        return $validation;
    }

    protected function prepareForValidation()
    {
        $variables = [];
        if (request()->filled('variables')) {
            foreach (request('variables') as $variable) {
                $index = $variable['variable'];

                $variables[$index] = $variable['option'];
            }
        }

        request()->merge(['variables' => $variables]);
        $this->request->replace(['variables' => $variables]);
    }
}
