<?php

namespace App\Http\Requests\Admin\Home;

use App\Models\Home;
use App\Models\Variable;
use App\Rules\HomeRasterImageUpload;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreHomeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Home::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $validations = [
            'user' => ['required', 'exists:users,id'],
            'code' => ['required', 'string', 'max:50'],
            'cover' => ['required', new HomeRasterImageUpload(), 'max:'.Home::MAX_IMAGE_SIZE],
            'reject_policy' => ['required', Rule::in(array_keys(Home::REJECT_POLICIES))],
            'status' => ['required', Rule::in(array_keys(Home::STATUSES))],
            'atmosphere' => ['required', Rule::in(array_keys(Home::ATMOSPHERES))],
            'type' => ['required', Rule::in(array_keys(Home::TYPES))],
            'area' => ['required', Rule::in(array_keys(Home::AREAS))],
            'province' => ['required', 'exists:provinces,id'],
            'city' => ['required', Rule::exists('cities', 'id')->where('province_id', request()->get('province'))],
            'address' => ['required', 'string', 'max:500'],
            'latitude' => ['required', 'numeric', 'max:1000'],
            'longitude' => ['required', 'numeric', 'max:1000'],
            'name' => ['required', 'string', 'max:250'],
            'description' => ['required', 'string', 'max:1500'],
            'rules' => ['nullable', 'string', 'max:500'],
            'yard' => ['required', 'numeric', 'min:1'],
            'infrastructure' => ['required', 'numeric', 'min:1'],
            'main_guest' => ['required', 'numeric', 'min:1'],
            'extra_guest' => ['required', 'numeric', 'min:0'],
            'week_price' => ['required', 'numeric', 'min:1000'],
            'thu_price' => ['required', 'numeric', 'min:1000'],
            'wed_price' => ['required', 'numeric', 'min:1000'],
            'fri_price' => ['required', 'numeric', 'min:1000'],
            'price_per_surplus' => ['required', 'numeric', 'min:0'],
            'off' => ['required', 'numeric', 'min:0', 'max:50'],
            'daily_off' => ['required', 'numeric', 'min:0', 'max:90'],
            'daily_off_amount' => ['required', Rule::in(array_keys(Home::DAILY_OFF_AMOUNTS))],
            'options' => ['nullable', 'exists:options,id'],
            'healths' => ['nullable', 'exists:healths,id'],
            'more_health' => ['nullable', 'string', 'max:250'],
            'shaba' => ['nullable', 'numeric', 'digits:24'],
            'document' => ['nullable', 'file', 'max:'.Home::MAX_IMAGE_SIZE],
            'safeties' => ['nullable', 'array'],
            'safeties.*.id' => ['nullable', 'exists:safeties,id'],
            'safeties.*.description' => ['nullable', 'string', 'max:250'],
            'more_safety' => ['nullable', 'string', 'max:250'],


            'sleep_room' => ['nullable', 'array'],
            'sleep_room.*.single_bed' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'sleep_room.*.double_bed' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'sleep_room.*.traditional_bed' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'sleep_room.*.more' => ['nullable', 'string', 'max:150'],

            'sleep_share' => ['nullable', 'array'],
            'sleep_share.single_bed' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'sleep_share.double_bed' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'sleep_share.traditional_bed' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'sleep_share.more' => ['nullable', 'string', 'max:150'],

            'sleep_area_description' => ['nullable', 'string', 'max:250']
        ];

        $validations += Variable::validation();

        return $validations;
    }
}
