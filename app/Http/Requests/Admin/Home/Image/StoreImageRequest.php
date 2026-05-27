<?php

namespace App\Http\Requests\Admin\Home\Image;

use App\Models\Home;
use App\Rules\HomeRasterImageUpload;
use Illuminate\Foundation\Http\FormRequest;

class StoreImageRequest extends FormRequest
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
