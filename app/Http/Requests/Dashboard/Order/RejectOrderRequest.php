<?php

namespace App\Http\Requests\Dashboard\Order;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RejectOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reject_reason' => ['required', Rule::in(array_keys(Order::REJECT_REASONS))],
        ];
    }

    public function messages(): array
    {
        return [
            'reject_reason.required' => 'لطفاً علت رد درخواست را انتخاب کنید.',
            'reject_reason.in' => 'علت رد درخواست معتبر نیست.',
        ];
    }
}
