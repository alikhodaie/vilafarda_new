<?php

namespace App\Http\Requests\Dashboard\Home;

use App\Models\Home;
use App\Rules\HomeRasterImageUpload;
use Illuminate\Foundation\Http\FormRequest;

class StoreHomeImageRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'file' => ['required', new HomeRasterImageUpload(), 'max:'.Home::MAX_IMAGE_SIZE],
        ];
    }
}
