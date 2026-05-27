<?php

namespace App\Models;

use App\Classes\Traits\HasCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FAQ extends Model
{
    use HasFactory, HasCategory;

    protected $guarded = [];

    protected $table = 'faq';

    # region Const
    const CACHE_KEY = 'faq';
    # endregion

    # region Scopes
    public function scopeSearch($query)
    {
        if (request()->filled('id')){
            $query->where('id', request()->get('id'));
        }
        if (request()->filled('question')){
            $query->where('question', 'LIKE', '%'.request()->get('question').'%');
        }
        if (request()->filled('category')){
            $query->whereHas('categories', function ($query){
                $query->where('id', request()->get('category'));
            });
        }

        return $query;
    }
    # endregion

    # region Methods
    public static function getFromCache()
    {
        return cache()->rememberForever(self::CACHE_KEY, function (){
            return Category::query()->FAQ()->whereHas('faq')->with(['faq' => function ($query){
                $query->orderBy('sort');
            }])->get();
        });
    }
    # endregion
}
