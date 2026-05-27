<?php

namespace App\Http\Requests\Admin\LandingPage;

use App\Models\Home;
use App\Models\LandingPage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLandingPageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', LandingPage::class);
    }

    public function rules(): array
    {
        return [
            'slug' => ['required', 'string', 'max:200', 'regex:/^[\pL\pN\-]+$/u', 'unique:landing_pages,slug'],
            'title' => ['required', 'string', 'max:255'],
            'meta_title' => ['nullable', 'string', 'max:60'],
            'meta_description' => ['nullable', 'string', 'max:150'],
            'intro' => ['nullable', 'string'],
            'filter_source_url' => ['nullable', 'string', 'max:2000'],
            'faq' => ['nullable', 'array'],
            'faq.*.question' => ['nullable', 'string', 'max:500'],
            'faq.*.answer' => ['nullable', 'string', 'max:5000'],
            'province' => ['nullable', 'exists:provinces,id'],
            'city' => ['nullable', 'exists:cities,id'],
            'home_type' => ['nullable', Rule::in(array_keys(Home::TYPES))],
            'city_only' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sort' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
