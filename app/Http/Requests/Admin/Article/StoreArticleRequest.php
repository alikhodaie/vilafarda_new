<?php

namespace App\Http\Requests\Admin\Article;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Article::class);
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
            'image'   => ['required', 'image', 'max:'.Article::MAX_IMAGE_SIZE]
        ];
    }

    public function attributes(): array
    {
        return [
            'title'    => 'عنوان',
            'slug'     => 'اسلاگ',
            'category' => 'دسته‌بندی',
            'summary'  => 'خلاصه',
            'content'  => 'محتوا',
            'metas'    => 'متا',
            'metas.*'  => 'متا',
            'tags'     => 'تگ',
            'tags.*'   => 'تگ',
            'image'    => 'تصویر',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'    => 'عنوان مقاله الزامی است.',
            'slug.required'     => 'اسلاگ مقاله الزامی است.',
            'category.required' => 'انتخاب دسته‌بندی الزامی است.',
            'category.exists'   => 'دسته‌بندی انتخاب‌شده معتبر نیست.',
            'summary.required'  => 'خلاصه مقاله الزامی است.',
            'summary.max'       => 'خلاصه مقاله نباید بیشتر از ۵۰۰ کاراکتر باشد.',
            'content.required'  => 'محتوای مقاله الزامی است.',
            'image.required'    => 'آپلود تصویر شاخص مقاله الزامی است.',
            'image.image'       => 'فایل تصویر باید از نوع تصویر باشد.',
            'image.max'         => 'حجم تصویر نباید بیشتر از ۲ مگابایت باشد.',
            'tags.*.exists'     => 'یکی از تگ‌های انتخاب‌شده معتبر نیست.',
        ];
    }
}
