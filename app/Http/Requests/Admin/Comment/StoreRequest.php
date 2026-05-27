<?php

namespace App\Http\Requests\Admin\Comment;

use App\Models\Comment;
use App\Models\Order;
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
        return $this->user()->can('create', Comment::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'status' => ['required', Rule::in(array_keys(Comment::STATUES))],
            'type' => ['required', Rule::in(['home', 'article'])],
            'comment' => ['required', 'string', 'max:3000'],
            'user' => ['required', 'exists:users,id'],
            'home' => [Rule::requiredIf(fn () => $this->get('type') === 'home'), Rule::exists('orders', 'home_id')
                ->where('status', Order::DONE)
                ->where('renter_id', $this->get('user'))
            ],
            'article' => [Rule::requiredIf(fn () => $this->get('type') === 'article'), 'exists:articles,id'],
            'score' => ['required', 'numeric', 'min:1', 'max:5'],
        ];
    }
}
