<?php

namespace App\Http\Requests\Admin\Article;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->article);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'   => ['required', 'string', 'max:250'],
            'slug'    => ['required', 'string', 'max:250'],
            'category' => ['required', 'numeric', Rule::exists('categories', 'id')->where('section', Category::ARTICLE)],
            'summary' => ['required', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'metas'   => ['nullable', 'array', 'min:1', 'max:100'],
            'metas.*' => ['required', 'string', 'max:50'],
            'tags'    => ['nullable', 'array', 'min:1', 'max:100'],
            'tags.*'  => ['required', 'numeric', 'exists:tags,id'],
            'image'   => ['nullable', 'image', 'max:'.Article::MAX_IMAGE_SIZE]
        ];
    }
}
