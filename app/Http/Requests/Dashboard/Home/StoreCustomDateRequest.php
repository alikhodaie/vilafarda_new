<?php

namespace App\Http\Requests\Dashboard\Home;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Morilog\Jalali\Jalalian;

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

    protected function prepareForValidation(): void
    {
        $isActive = $this->input('is_active');

        if (is_bool($isActive)) {
            $this->merge(['is_active' => $isActive ? 'true' : 'false']);
        }

        // Vue v-model on a hidden input sends an empty string when the switch is off.
        if ($isActive === '' || $isActive === null) {
            $this->merge(['is_active' => 'false']);
        }

        if (! $this->has('dates') || ! is_array($this->input('dates'))) {
            return;
        }

        $dates = collect($this->input('dates'))
            ->filter(fn ($date) => filled($date))
            ->map(fn ($date) => $this->normalizeDate($date))
            ->values()
            ->all();

        $this->merge(['dates' => $dates]);
    }

    private function normalizeDate(mixed $value): string
    {
        $value = trim((string) $value);
        $value = preg_replace('/\s.*$/', '', $value);
        $value = str_replace('-', '/', $value);

        if (! preg_match('#^(\d{4})/(\d{1,2})/(\d{1,2})$#', $value, $parts)) {
            return Carbon::parse($value)->format('Y/m/d');
        }

        $year = (int) $parts[1];
        $month = (int) $parts[2];
        $day = (int) $parts[3];

        if ($year >= 1300 && $year < 1500) {
            return Jalalian::fromFormat('Y/m/d', sprintf('%04d/%02d/%02d', $year, $month, $day))
                ->toCarbon()
                ->format('Y/m/d');
        }

        return sprintf('%04d/%02d/%02d', $year, $month, $day);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $deactivating = $this->input('is_active') === 'false';

        return [
            'dates' => ['required', 'array', 'min:1', 'max:100'],
            'dates.*' => [
                'required',
                'date_format:Y/m/d',
                'after_or_equal:'.Order::getMinReserveDate()->format('Y/m/d'),
            ],
            'is_active' => ['required', 'in:true,false'],
            'min_nights' => ['required', 'integer', 'min:1', 'max:30'],
            'price' => $deactivating
                ? ['nullable', 'numeric', 'min:0']
                : ['required', 'numeric', 'min:1000'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->input('is_active') === 'false' || (int) $this->input('min_nights', 1) <= 1) {
                return;
            }

            /** @var \App\Models\Home $home */
            $home = $this->route('home');
            $minNights = (int) $this->input('min_nights');

            foreach ($this->input('dates', []) as $date) {
                $explanation = $home->explainMinNightsLimitation($date, $minNights);

                if (! $explanation) {
                    continue;
                }

                if (in_array($explanation['reason'], ['host_closed_checkin', 'order_checkin'], true)) {
                    $validator->errors()->add('min_nights', $explanation['message']);
                }
            }
        });
    }
}
