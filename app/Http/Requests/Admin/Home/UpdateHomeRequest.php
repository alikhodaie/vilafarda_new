<?php

namespace App\Http\Requests\Admin\Home;

use App\Models\Home;
use App\Models\Variable;
use App\Rules\HomeRasterImageUpload;
use App\Support\UploadValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateHomeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->home);
    }

    protected function prepareForValidation(): void
    {
        $priceFields = ['week_price', 'wed_price', 'thu_price', 'fri_price', 'price_per_surplus', 'cleaning_fee'];
        $normalizedPrices = [];

        foreach ($priceFields as $field) {
            if ($this->has($field) && $this->input($field) !== null && $this->input($field) !== '') {
                $raw = (string) $this->input($field);
                $raw = str_replace(['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', '٬', ',', ' '], ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '', '', ''], $raw);
                $normalizedPrices[$field] = (int) round((float) preg_replace('/[^\d.]/', '', $raw));
            }
        }

        if ($normalizedPrices !== []) {
            $this->merge($normalizedPrices);
        }

        if ($this->has('slug')) {
            $slug = Home::normalizeSlug(trim((string) $this->input('slug')));
            $this->merge(['slug' => $slug !== '' ? $slug : null]);
        }

        if ($this->files->has('gallery')) {
            $raw = $this->files->get('gallery');
            $normalized = UploadValidation::normalizeFileList($raw);
            if ($normalized !== []) {
                $this->files->set('gallery', $normalized);
            }
        }

        $this->convertedFiles = null;
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
            'cover' => ['nullable', new HomeRasterImageUpload(), 'max:'.Home::MAX_IMAGE_SIZE],
            'cover_alt' => ['nullable', 'string', 'max:255'],
            'image_alts' => ['nullable', 'array'],
            'image_alts.*' => ['nullable', 'string', 'max:255'],
            'gallery' => ['nullable', 'array'],
            'video' => ['nullable', 'file', 'mimetypes:video/x-flv,video/mp4,application/x-mpegURL,video/MP2T,video/3gpp,video/quicktime,video/x-msvideo,video/x-ms-wmv', 'max:'.Home::MAX_VIDEO_SIZE],
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
            'slug' => ['nullable', 'string', 'max:200'],
            'score' => ['required', 'numeric', 'min:1', 'max:5'],
            'description' => ['required', 'string', 'max:1500'],
            'rules' => ['nullable', 'string', 'max:500'],
            'yard' => ['required', 'numeric', 'min:1'],
            'infrastructure' => ['required', 'numeric', 'min:1'],
            'main_guest' => ['required', 'numeric', 'min:1'],
            'extra_guest' => ['required', 'numeric', 'min:0'],
            'week_price' => ['required', 'numeric', 'min:1000'],
            'wed_price' => ['required', 'numeric', 'min:1000'],
            'thu_price' => ['required', 'numeric', 'min:1000'],
            'fri_price' => ['required', 'numeric', 'min:1000'],
            'price_per_surplus' => ['required', 'numeric', 'min:0'],
            'cleaning_fee' => ['nullable', 'numeric', 'min:0'],
            'delete_existing_images' => ['nullable', 'array'],
            'delete_existing_images.*' => ['integer', Rule::exists('home_images', 'id')->where('home_id', $this->home->id)],
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

            'sleep_area_description' => ['nullable', 'string', 'max:250']
        ];

        $validations += Variable::validation();

        return $validations;
    }
}
