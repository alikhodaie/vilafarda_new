<?php

namespace App\Http\Requests\Dashboard\Home\Validate;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStep7Request extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'rooms' => ['nullable', 'array'],
            'rooms.*.id' => ['nullable', Rule::exists('home_sleep_places', 'id')->where('home_id', $this->home->id)],
            'rooms.*.single_bed' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'rooms.*.double_bed' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'rooms.*.traditional_bed' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'rooms.*.more' => ['nullable', 'string', 'max:150'],

            'share_room' => ['nullable', 'array'],
            'share_room.single_bed' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'share_room.double_bed' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'share_room.traditional_bed' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'share_room.more' => ['nullable', 'string', 'max:150'],

            'sleep_area_description' => ['nullable', 'string', 'max:250']
        ];
    }
}
