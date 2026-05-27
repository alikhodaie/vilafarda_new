<?php

namespace App\Http\Requests\Dashboard\Rent;

use App\Services\RentReviewService;
use App\Support\HomeReviewCriteria;
use Illuminate\Foundation\Http\FormRequest;

class StoreHomeReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        $order = $this->route('order');

        if (! $order || ! auth()->check()) {
            return false;
        }

        return app(RentReviewService::class)->canSubmit($order, (int) auth()->id());
    }

    public function rules(): array
    {
        $rules = [
            'comment' => ['nullable', 'string', 'max:500'],
        ];

        foreach (HomeReviewCriteria::KEYS as $key) {
            $rules["scores.{$key}"] = ['required', 'integer', 'min:1', 'max:5'];
        }

        return $rules;
    }

    public function attributes(): array
    {
        $attributes = [
            'comment' => 'متن نظر',
        ];

        foreach (HomeReviewCriteria::labels() as $key => $label) {
            $attributes["scores.{$key}"] = $label;
        }

        return $attributes;
    }

    public function messages(): array
    {
        return [
            'scores.*.required' => 'لطفاً به همه موارد امتیاز دهید.',
        ];
    }
}
