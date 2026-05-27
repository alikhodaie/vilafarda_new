<?php

namespace App\Http\Requests\Dashboard\Home;

use App\Models\Health;
use App\Models\Home;
use App\Models\Option;
use App\Models\Variable;
use App\Rules\HomeRasterImageUpload;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->home->user_id == $this->user()->id;
    }

    protected function prepareForValidation(): void
    {
        $fields = ['week_price', 'wed_price', 'thu_price', 'fri_price', 'price_per_surplus', 'cleaning_fee'];
        $normalized = [];

        foreach ($fields as $field) {
            if ($this->has($field) && $this->input($field) !== null && $this->input($field) !== '') {
                $normalized[$field] = (int) round((float) $this->input($field));
            }
        }

        if ($normalized !== []) {
            $this->merge($normalized);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $validations = [
            'name' => ['required', 'string', 'max:250'],
            'description' => ['required', 'string', 'max:1500'],
            'main_guest' => ['required', 'numeric', 'min:1'],
            'type' => ['required', Rule::in(array_keys(Home::TYPES))],
            
            // Location fields
            'province_id' => ['required', 'exists:provinces,id'],
            'city_id' => ['required', 'exists:cities,id'],
            'address' => ['required', 'string', 'max:500'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            
            // Pricing fields
            'week_price' => ['required', 'numeric', 'min:1000'],
            'price_per_surplus' => ['nullable', 'numeric', 'min:0'],
            'wed_price' => ['nullable', 'numeric', 'min:0'],
            'thu_price' => ['nullable', 'numeric', 'min:0'],
            'fri_price' => ['nullable', 'numeric', 'min:0'],
            'cleaning_fee' => ['nullable', 'numeric', 'min:0'],

            // Images
            'cover' => ['nullable', new HomeRasterImageUpload(), 'max:'.Home::MAX_IMAGE_SIZE],
            'images' => [
                'nullable',
                'array',
                'max:30',
                function ($attribute, $value, $fail) {
                    $existingCount = $this->home->images()->count();
                    $deleteCount = count($this->get('delete_existing_images', []));
                    $newCount = is_array($value) ? count($value) : 0;
                    $finalCount = max(0, $existingCount - $deleteCount) + $newCount;

                    if ($finalCount > 30) {
                        $fail('تعداد کل تصاویر نباید بیشتر از 30 عدد باشد.');
                    }
                }
            ],
            'delete_existing_images' => ['nullable', 'array'],
            'delete_existing_images.*' => ['integer', Rule::exists('home_images', 'id')->where('home_id', $this->home->id)],
            
            // Optional fields
            'reject_policy' => ['nullable', Rule::in(array_keys(Home::REJECT_POLICIES))],
            'rules' => ['nullable', 'string', 'max:500'],
            'yard' => ['nullable', 'numeric', 'min:1'],
            'infrastructure' => ['nullable', 'numeric', 'min:1'],
            'extra_guest' => ['nullable', 'numeric', 'min:0'],
            'atmosphere' => ['nullable', Rule::in(array_keys(Home::ATMOSPHERES))],
            'area' => ['nullable', Rule::in(array_keys(Home::AREAS))],


            'sleep_room' => ['nullable', 'array'],
            'sleep_room.*.id' => ['nullable', Rule::exists('home_sleep_places', 'id')->where('home_id', $this->home->id)],
            'sleep_room.*.single_bed' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'sleep_room.*.double_bed' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'sleep_room.*.traditional_bed' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'sleep_room.*.more' => ['nullable', 'string', 'max:150'],

            'sleep_share' => ['nullable', 'array'],
            'sleep_share.single_bed' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'sleep_share.double_bed' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'sleep_share.traditional_bed' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'sleep_share.more' => ['nullable', 'string', 'max:150'],

            'sleep_area_description' => ['nullable', 'string', 'max:250'],

            'off' => ['nullable', 'numeric', 'min:0', 'max:50'],
            'daily_off' => ['nullable', 'numeric', 'min:0', 'max:90'],
            'daily_off_amount' => ['nullable', Rule::in(array_keys(Home::DAILY_OFF_AMOUNTS))],
            'more_health' => ['nullable', 'string', 'max:250'],
            'more_safety' => ['nullable', 'string', 'max:250'],
            'options' => ['nullable', 'array', 'max:'.Option::getFromCache()->count()],
            'options.*' => ['nullable', 'exists:options,id'],
            'healths' => ['nullable', 'array', 'max:'.Health::getFromCache()->count()],
            'healths.*' => ['nullable', 'exists:healths,id'],
            'safeties' => ['nullable', 'array'],
            'safeties.*.id' => ['nullable', 'exists:safeties,id'],
            'safeties.*.description' => ['nullable', 'string', 'max:250'],
            'document' => ['nullable', 'file', 'max:'.Home::MAX_IMAGE_SIZE],
        ];

        $validations += Variable::validation();

        return $validations;
    }
}
