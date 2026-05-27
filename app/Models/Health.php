<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Health extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = true;

    const CACHE_KEY = 'healths';

    public static function getFromCache(): Collection
    {
        return cache()->rememberForever(self::CACHE_KEY, function (): Collection {
            return self::all();
        });
    }

    # region Accessories
    public function scopeSearch($query)
    {
        if (request()->filled('id')){
            $query->where('id', request('id'));
        }
        if (request()->filled('title')){
            $query->where('title', 'LIKE', '%'.request('title').'%');
        }

        return $query;
    }
    # endregion

    # region Relations
    public function homes()
    {
        $this->belongsToMany(Home::class, 'home_health');
    }
    # endregion
}
