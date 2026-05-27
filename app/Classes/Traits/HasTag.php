<?php


namespace App\Classes\Traits;


use App\Models\Tag;

trait HasTag
{
    /**
     * Get all of the tags for specific collection.
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
