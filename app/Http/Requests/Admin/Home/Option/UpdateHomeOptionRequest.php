<?php

namespace App\Http\Requests\Admin\Home\Option;

use App\Models\Option;
use App\Rules\BootstrapIconClass;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\RequiredIf;

class UpdateHomeOptionRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('update', $this->option);
    }

    public function rules()
    {
        return [
            'icon_type' => ['required', Rule::in([Option::ICON_TYPE_IMAGE, Option::ICON_TYPE_FONT])],
            'icon_class' => ['required_if:icon_type,'.Option::ICON_TYPE_FONT, 'nullable', new BootstrapIconClass],
            'icon' => [
                Rule::requiredIf(fn () => $this->get('icon_type') === Option::ICON_TYPE_IMAGE && $this->option->isFontIcon()),
                'nullable',
                'image',
                'max:'.Option::MAX_FILE_SIZE,
            ],
            'title' => ['required', 'string', 'max:250'],
        ];
    }
}
