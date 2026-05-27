<?php

namespace App\Http\Requests\Main;

use App\Models\Order;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReserveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
            'date' => ['required', 'array'],
            'date.*' => ['required', 'date', 'after_or_equal:'.Order::getMinReserveDate(), 'before_or_equal:'.Order::getMaxReserveDate()],
            'guests' => ['required', 'numeric', 'min:1', 'max:' . ($this->home->main_guest + $this->home->extra_guest)]
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (! $this->filled('start_date') || ! $this->filled('end_date')) {
                return;
            }

            $start = Carbon::parse($this->input('start_date'))->startOfDay();
            $end = Carbon::parse($this->input('end_date'))->startOfDay();
            $nights = $start->diffInDays($end);
            $minNights = $this->home->getEffectiveMinNightsForDate($start);

            if ($nights < $minNights) {
                $validator->errors()->add(
                    'end_date',
                    'حداقل '.persianNumber($minNights).' شب برای رزرو از این تاریخ لازم است.'
                );
            }
        });
    }

    protected function prepareForValidation()
    {
        if ($this->request->get('start_date') && $this->request->get('end_date')){
            $start = Carbon::parse($this->request->get('start_date'));
            $end = Carbon::parse($this->request->get('end_date'))->subDay();

            $dates = [];
            $period = CarbonPeriod::create($start, $end);
            foreach ($period as $date){
                $dates[] = $date->format('Y/m/d');
            }

            $this->request->set('date', $dates);
        }
    }
}
