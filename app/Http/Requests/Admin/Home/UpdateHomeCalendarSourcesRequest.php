<?php

namespace App\Http\Requests\Admin\Home;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateHomeCalendarSourcesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('updateCalendarSync', \App\Models\Home::class);
    }

    public function rules(): array
    {
        return [
            'sources' => ['required', 'array'],
            'sources.*.external_url' => ['nullable', 'string', 'max:500', 'url'],
            'sources.*.platform' => ['nullable', 'string', Rule::in(array_keys(\App\Support\ExternalCalendarPlatform::labels()))],
            'sources.*.sync_enabled' => ['nullable', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'sources.*.external_url' => 'لینک خارجی',
            'sources.*.platform' => 'پلتفرم',
            'sources.*.sync_enabled' => 'همگام‌سازی خودکار',
        ];
    }
}
