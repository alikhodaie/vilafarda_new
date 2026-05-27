<?php

namespace App\Http\Requests\Dashboard\Home;

use App\Models\Home;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateHostStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'action' => ['required', Rule::in(['activate', 'deactivate'])],
            'reason' => [
                Rule::requiredIf($this->input('action') === 'deactivate'),
                'nullable',
                Rule::in(array_keys(Home::HOST_DEACTIVATION_REASONS)),
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'reason' => 'دلیل غیرفعال‌سازی',
            'action' => 'عملیات',
        ];
    }
}
