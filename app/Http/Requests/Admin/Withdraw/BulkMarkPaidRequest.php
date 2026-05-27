<?php

namespace App\Http\Requests\Admin\Withdraw;

use Illuminate\Foundation\Http\FormRequest;

class BulkMarkPaidRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->hasPermissionTo('withdraws:update');
    }

    public function rules()
    {
        return [
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:host_payouts,id'],
        ];
    }
}
