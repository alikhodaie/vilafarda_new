<?php

namespace App\Http\Requests\Dashboard\Comment;

use App\Models\Comment;
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
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'reply_id' => ['required', 'numeric', Rule::exists('comments', 'id')->where('status', Comment::CONFIRMED)],
            'comment'  => ['required', 'string', 'max:250']
        ];
    }
}
