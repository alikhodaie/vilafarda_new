<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Consultant extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $with = ['province', 'city'];

    const MAX_IMAGE_SIZE = 1024 * 2;
    const IMAGE_PATH = 'files/consultant/';
    const CACHE_KEY = 'consultants';

    # region Methods
    public static function getFromCache()
    {
        return cache()->rememberForever(self::CACHE_KEY, function (){
            return Consultant::query()->oldest()->get();
        });
    }
    # endregion

    # region Accessories
    public function getImagePathAttribute(): string
    {
        return $this->image ? asset(self::IMAGE_PATH.$this->image) : asset('assets/images/avatar.jpg');
    }
    # endregion

    # region Relations
    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
    # endregion
}
