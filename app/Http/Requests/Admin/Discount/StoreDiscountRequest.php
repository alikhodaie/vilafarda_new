<?php

namespace App\Http\Requests\Admin\Discount;

use App\Models\Discount;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDiscountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Discount::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $max = ($this->get('type') === Discount::PERCENT)
            ? 99
            : 100000000;

        return [
            'code' => ['required', 'string', 'max:50', 'unique:discounts,code'],
            'expired_at' => ['required', 'date', 'after:'.now()],
            'type' => ['required', Rule::in(collect(Discount::TYPES)->pluck('value'))],
            'amount' => ['required', 'numeric', 'min:1', "max:$max"],
            'user_type' => ['required', Rule::in(collect(Discount::USER_TYPES)->pluck('value'))],
            'users' => ['nullable', Rule::requiredIf(fn () => $this->get('user_type') === Discount::OLD_USERS), 'in:all,has_orders,has_not_orders,owners,selected'],
            'users_list' => ['nullable', Rule::requiredIf(fn () => $this->get('users') === 'selected'), 'array', 'min:1'],
            'users_list.*' => ['nullable', Rule::requiredIf(fn () => $this->get('users') === 'selected'), 'exists:users,id'],
            'start_date' => ['nullable', Rule::requiredIf(fn () => $this->get('users') === 'has_orders'), 'before:'.now()->addDay()],
            'end_date' => ['nullable', Rule::requiredIf(fn () => $this->get('users') === 'has_orders'), 'before:'.now()->addDay(), 'after:'. $this->get('start_date')],
            'sms_type' => ['nullable', 'string', 'in:pattern,custom'],
            'sms' => ['nullable', Rule::requiredIf(fn () => filled($this->get('sms_type'))), 'string', 'max:160'],
        ];
    }
}
