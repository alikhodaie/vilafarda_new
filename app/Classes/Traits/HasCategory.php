<?php


namespace App\Classes\Traits;


use App\Models\Category;

trait HasCategory
{
    public function categories()
    {
        return $this->morphToMany(Category::class, 'categorable');
    }

    public function getCategoryAttribute()
    {
        return $this->categories()->first();
    }
}
