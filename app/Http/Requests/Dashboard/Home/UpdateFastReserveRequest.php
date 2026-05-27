<?php

namespace App\Http\Requests\Dashboard\Home;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFastReserveRequest extends FormRequest
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
        return [
            'min_date' => ['nullable', Rule::requiredIf(function (){ return request()->filled('date'); }), 'date', 'after_or_equal:'. Order::getMinReserveDate()],
            'max_date' => ['nullable', Rule::requiredIf(function (){ return request()->filled('date'); }), 'date'],
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->request->get('date')){
            $period = explode(',', $this->request->get('date'));
            $min = $period[0];
            $max = $period[1];

            $this->request->add([
                'min_date' => $min,
                'max_date' => $max,
            ]);
        }
    }
}
