<?php

namespace App\Http\Requests\Dashboard\Home;

use App\Models\Health;
use App\Models\Home;
use App\Models\Option;
use App\Models\Variable;
use App\Rules\HomeRasterImageUpload;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->home->is_draft && $this->home->user_id == $this->user()->id;
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

    public function messages()
    {
        return [
            'name.required' => 'نام اقامتگاه الزامی است',
            'description.required' => 'توضیحات الزامی است',
            'type.required' => 'نوع اقامتگاه الزامی است',
            'type.in' => 'نوع اقامتگاه باید یکی از موارد: ویلایی, آپارتمان, سوئیت باشد',
            'main_guest.required' => 'تعداد مهمان اصلی الزامی است',
            'main_guest.numeric' => 'تعداد مهمان اصلی باید عدد باشد',
            'main_guest.min' => 'تعداد مهمان اصلی باید حداقل 1 باشد',
            'main_guest.max' => 'تعداد مهمان اصلی باید حداکثر 50 باشد',
            'province_id.required' => 'انتخاب استان الزامی است',
            'province_id.exists' => 'استان انتخاب شده معتبر نیست',
            'city_id.required' => 'انتخاب شهر الزامی است',
            'city_id.exists' => 'شهر انتخاب شده معتبر نیست',
            'address.required' => 'آدرس الزامی است',
            'week_price.required' => 'قیمت اول هفته (شنبه تا سه‌شنبه) الزامی است',
            'week_price.numeric' => 'قیمت اول هفته باید عدد باشد',
            'week_price.min' => 'قیمت اول هفته باید حداقل 1000 تومان باشد',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
//        $validations = [
//            'reject_policy' => ['required', Rule::in(array_keys(Home::REJECT_POLICIES))],
//            'province' => ['required', 'exists:provinces,id'],
//            'city' => ['required', Rule::exists('cities', 'id')->where('province_id', request()->get('province'))],
//            'address' => ['required', 'string', 'max:500'],
//            'latitude' => ['required', 'numeric', 'max:1000'],
//            'longitude' => ['required', 'numeric', 'max:1000'],
//            'name' => ['required', 'string', 'max:250'],
//            'description' => ['required', 'string', 'max:1500'],
//            'rules' => ['nullable', 'string', 'max:500'],
//            'yard' => ['required', 'numeric', 'min:1'],
//            'infrastructure' => ['required', 'numeric', 'min:1'],
//            'main_guest' => ['required', 'numeric', 'min:1'],
//            'extra_guest' => ['required', 'numeric', 'min:0'],
//            'week_price' => ['required', 'numeric', 'min:1000'],
//            'wed_price' => ['required', 'numeric', 'min:1000'],
//            'thu_price' => ['required', 'numeric', 'min:1000'],
//            'fri_price' => ['required', 'numeric', 'min:1000'],
//            'price_per_surplus' => ['required', 'numeric', 'min:0'],
//            'options' => ['nullable', 'exists:options,id']
//        ];
//
//        $validations += Variable::validation();

        return [
            'name' => ['required', 'string', 'max:250'],
            'description' => ['required', 'string', 'max:1500'],
            'type' => ['required', 'string', 'in:vilaiy,aparteman,swiit'],
            'main_guest' => ['required', 'numeric', 'min:1', 'max:50'],
            'extra_guest' => ['nullable', 'numeric', 'min:0', 'max:50'],
            'atmosphere' => ['nullable', Rule::in(array_keys(Home::ATMOSPHERES))],
            'area' => ['nullable', Rule::in(array_keys(Home::AREAS))],
            'yard' => ['nullable', 'numeric', 'min:0'],
            'infrastructure' => ['nullable', 'numeric', 'min:0'],
            'sleep_room' => ['nullable', 'array', 'max:20'],
            'sleep_room.*.id' => ['nullable', Rule::exists('home_sleep_places', 'id')->where('home_id', $this->home->id)],
            'sleep_room.*.single_bed' => ['nullable', 'integer', 'min:0', 'max:100'],
            'sleep_room.*.double_bed' => ['nullable', 'integer', 'min:0', 'max:100'],
            'sleep_room.*.traditional_bed' => ['nullable', 'integer', 'min:0', 'max:100'],
            'sleep_room.*.more' => ['nullable', 'string', 'max:150'],
            'sleep_area_description' => ['nullable', 'string', 'max:500'],
            'province_id' => ['required', 'exists:provinces,id'],
            'city_id' => ['required', 'exists:cities,id'],
            'address' => ['required', 'string', 'max:500'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'week_price' => ['required', 'numeric', 'min:1000'],
            'wed_price' => ['nullable', 'numeric', 'min:0'],
            'thu_price' => ['nullable', 'numeric', 'min:0'],
            'fri_price' => ['nullable', 'numeric', 'min:0'],
            'price_per_surplus' => ['nullable', 'numeric', 'min:0'],
            'security_deposit' => ['nullable', 'numeric', 'min:0'],
            'cleaning_fee' => ['nullable', 'numeric', 'min:0'],
            'cover' => ['nullable', new HomeRasterImageUpload(), 'max:'.Home::MAX_IMAGE_SIZE],
            'images' => ['nullable', 'array'],
            'document' => ['nullable', Rule::requiredIf($this->home->document), 'file', 'max:'.Home::MAX_IMAGE_SIZE],
            'off' => ['nullable', 'numeric', 'min:0', 'max:50'],
            'daily_off' => ['nullable', 'numeric', 'min:0', 'max:90'],
            'daily_off_amount' => ['nullable', Rule::in(array_keys(Home::DAILY_OFF_AMOUNTS))],
            'reject_policy' => ['nullable', Rule::in(array_keys(Home::REJECT_POLICIES))],
            'rules' => ['nullable', 'string', 'max:500'],
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
    }
}
