<?php

namespace App\Http\Requests\Admin\Home\Option;

use App\Models\Option;
use App\Rules\BootstrapIconClass;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreHomeOptionRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('create', Option::class);
    }

    public function rules()
    {
        return [
            'icon_type' => ['required', Rule::in([Option::ICON_TYPE_IMAGE, Option::ICON_TYPE_FONT])],
            'icon_class' => ['required_if:icon_type,'.Option::ICON_TYPE_FONT, 'nullable', new BootstrapIconClass],
            'icon' => ['required_if:icon_type,'.Option::ICON_TYPE_IMAGE, 'nullable', 'image', 'max:'.Option::MAX_FILE_SIZE],
            'title' => ['required', 'string', 'max:250'],
        ];
    }
}
