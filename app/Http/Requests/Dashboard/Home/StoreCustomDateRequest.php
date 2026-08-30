<?php

namespace App\Http\Requests\Dashboard\Home;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;

class StoreCustomDateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->id == $this->home->user_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $isResetBase = $this->input('calendar_action') === 'reset_base';
        $isClosingDays = $this->input('is_active') === 'false';

        return [
            'dates' => ['required', 'array', 'min:0', 'max:100'],
            'dates.*' => ['required', 'date_format:Y/m/d', 'after_or_equal:'.Order::getMinReserveDate()],
            'calendar_action' => ['nullable', 'in:save,reset_base'],
            'is_active' => [$isResetBase ? 'nullable' : 'required', 'in:true,false'],
            'price' => ($isResetBase || $isClosingDays)
                ? ['nullable', 'numeric', 'min:0']
                : ['required', 'numeric', 'min:1000'],
            'week_price' => ['nullable', 'numeric', 'min:1000'],
            'wed_price' => ['nullable', 'numeric', 'min:1000'],
            'thu_price' => ['nullable', 'numeric', 'min:1000'],
            'fri_price' => ['nullable', 'numeric', 'min:1000'],
        ];
    }
}
