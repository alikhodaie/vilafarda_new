<?php

namespace App\Http\Requests\Dashboard\Home;

use App\Models\Health;
use App\Models\Home;
use App\Models\Option;
use App\Rules\HomeRasterImageUpload;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveMobileDraftRequest extends FormRequest
{
    public function authorize(): bool
    {
        $home = $this->route('home');

        return $home instanceof Home
            && (int) $home->user_id === (int) $this->user()->id
            && $home->is_draft;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('latitude') && $this->input('latitude') === '') {
            $this->merge(['latitude' => null]);
        }
        if ($this->has('longitude') && $this->input('longitude') === '') {
            $this->merge(['longitude' => null]);
        }

        $priceFields = ['week_price', 'wed_price', 'thu_price', 'fri_price', 'price_per_surplus', 'cleaning_fee'];
        $normalized = [];

        foreach ($priceFields as $field) {
            if ($this->has($field) && $this->input($field) !== null && $this->input($field) !== '') {
                $normalized[$field] = (int) round((float) $this->input($field));
            }
        }

        if ($normalized !== []) {
            $this->merge($normalized);
        }
    }

    public function rules(): array
    {
        $step = (int) $this->input('step', 0);
        $optionsCount = Option::getFromCache()->count();
        $healthsCount = Health::getFromCache()->count();
        $home = $this->route('home');

        switch ($step) {
            case 1:
                return [
                    'step' => ['required', 'integer', 'in:1'],
                    'name' => ['required', 'string', 'max:250'],
                    'description' => ['required', 'string', 'max:1500'],
                    'type' => ['required', 'string', 'in:vilaiy,aparteman,swiit'],
                    'main_guest' => ['required', 'integer', 'min:1', 'max:50'],
                    'extra_guest' => ['nullable', 'integer', 'min:0', 'max:50'],
                    'atmosphere' => ['nullable', Rule::in(array_keys(Home::ATMOSPHERES))],
                    'area' => ['nullable', Rule::in(array_keys(Home::AREAS))],
                    'yard' => ['nullable', 'numeric', 'min:0'],
                    'infrastructure' => ['nullable', 'numeric', 'min:0'],
                ];
            case 2:
                return [
                    'step' => ['required', 'integer', 'in:2'],
                    'cover' => ['nullable', new HomeRasterImageUpload(), 'max:'.Home::MAX_IMAGE_SIZE],
                    'images' => ['nullable', 'array'],
                    'images.*' => ['nullable', new HomeRasterImageUpload(), 'max:'.Home::MAX_IMAGE_SIZE],
                ];
            case 3:
                return [
                    'step' => ['required', 'integer', 'in:3'],
                    'sleep_room' => ['nullable', 'array', 'max:20'],
                    'sleep_room.*.id' => ['nullable', Rule::exists('home_sleep_places', 'id')->where('home_id', $home->id)],
                    'sleep_room.*.single_bed' => ['nullable', 'integer', 'min:0', 'max:100'],
                    'sleep_room.*.double_bed' => ['nullable', 'integer', 'min:0', 'max:100'],
                    'sleep_room.*.traditional_bed' => ['nullable', 'integer', 'min:0', 'max:100'],
                    'sleep_room.*.more' => ['nullable', 'string', 'max:150'],
                    'sleep_area_description' => ['nullable', 'string', 'max:500'],
                ];
            case 4:
                return [
                    'step' => ['required', 'integer', 'in:4'],
                    'province_id' => ['required', 'exists:provinces,id'],
                    'city_id' => ['required', 'exists:cities,id'],
                    'address' => ['required', 'string', 'max:500'],
                    'latitude' => ['nullable', 'numeric', 'between:-90,90'],
                    'longitude' => ['nullable', 'numeric', 'between:-180,180'],
                ];
            case 5:
                return [
                    'step' => ['required', 'integer', 'in:5'],
                    'week_price' => ['required', 'numeric', 'min:1000'],
                    'wed_price' => ['nullable', 'numeric', 'min:0'],
                    'thu_price' => ['nullable', 'numeric', 'min:0'],
                    'fri_price' => ['nullable', 'numeric', 'min:0'],
                    'price_per_surplus' => ['nullable', 'numeric', 'min:0'],
                    'cleaning_fee' => ['nullable', 'numeric', 'min:0'],
                ];
            case 6:
                return [
                    'step' => ['required', 'integer', 'in:6'],
                    'off' => ['nullable', 'numeric', 'min:0', 'max:50'],
                    'daily_off' => ['nullable', 'numeric', 'min:0', 'max:90'],
                    'daily_off_amount' => ['nullable', Rule::in(array_keys(Home::DAILY_OFF_AMOUNTS))],
                ];
            case 7:
                return [
                    'step' => ['required', 'integer', 'in:7'],
                    'options' => ['nullable', 'array', 'max:'.$optionsCount],
                    'options.*' => ['nullable', 'exists:options,id'],
                ];
            case 8:
                return [
                    'step' => ['required', 'integer', 'in:8'],
                    'safeties' => ['nullable', 'array'],
                    'safeties.*.id' => ['nullable', 'exists:safeties,id'],
                    'safeties.*.description' => ['nullable', 'string', 'max:250'],
                    'more_safety' => ['nullable', 'string', 'max:250'],
                ];
            case 9:
                return [
                    'step' => ['required', 'integer', 'in:9'],
                    'healths' => ['nullable', 'array', 'max:'.$healthsCount],
                    'healths.*' => ['nullable', 'exists:healths,id'],
                    'more_health' => ['nullable', 'string', 'max:250'],
                ];
            case 10:
                return [
                    'step' => ['required', 'integer', 'in:10'],
                    'reject_policy' => ['nullable', Rule::in(array_keys(Home::REJECT_POLICIES))],
                    'rules' => ['nullable', 'string', 'max:500'],
                ];
            case 11:
                return [
                    'step' => ['required', 'integer', 'in:11'],
                    'document' => ['nullable', 'file', 'max:'.Home::MAX_IMAGE_SIZE],
                ];
            default:
                return [
                    'step' => ['required', 'integer', 'in:1,2,3,4,5,6,7,8,9,10,11'],
                ];
        }
    }
}
