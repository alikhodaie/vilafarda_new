<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

    const HOME = 'home';
    const ARTICLE = 'article';
    const FAQ = 'faq';
    const SECTIONS = [
        self::HOME,
        self::FAQ,
        self::ARTICLE
    ];

    # region Scopes
    public function scopeArticle($query)
    {
        return $query->where('section', self::ARTICLE);
    }

    public function scopeHome($query)
    {
        return $query->where('section', self::HOME);
    }

    public function scopeFAQ($query)
    {
        return $query->where('section', self::FAQ);
    }

    public function scopeParent($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeSearch($query)
    {
        if (request()->filled('id')){
            $query->where('id', request()->get('id'));
        }
        if (request()->filled('title')){
            $query->where('title', 'LIKE', '%'.request()->get('title').'%');
        }
        if (request()->filled('parent')){
            $query->where('parent_id', request()->get('parent'));
        }

        return $query;
    }
    # endregion

    # region Relations
    public function articles()
    {
        return $this->morphedByMany(Article::class, 'categorable');
    }

    public function homes()
    {
        return $this->morphedByMany(Home::class, 'categorable');
    }

    public function faq()
    {
        return $this->morphedByMany(FAQ::class, 'categorable');
    }

    /**
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
    # endregion
}
