<?php

namespace App\Http\Requests\Admin\Newsletter;

use App\Models\Newsletter;
use App\Models\NewsletterSubscriber;
use Illuminate\Foundation\Http\FormRequest;

class NewsletterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Newsletter::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => ['required', 'string', 'max:190'],
            'body'  => ['required', 'string'],
        ];
    }
}
