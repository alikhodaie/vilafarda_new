<?php

namespace App\Http\Requests\Main\Comment;

use App\Models\Comment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->home){
            return $this->user()->isRent($this->home);
        }

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
            'reply_id' => ['nullable', 'numeric', Rule::exists('comments', 'id')->where('status', Comment::CONFIRMED)],
            'score' => ['required', 'numeric', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'max:250'],
            'name' => ['required', 'string', 'max:250'],
            'email' => ['required', 'email', 'max:250']
        ];
    }
}
