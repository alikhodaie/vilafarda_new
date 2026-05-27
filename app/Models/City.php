<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    # region variables
    protected $guarded = [];

    public $timestamps = false;
    # endregion

    # region relations
    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function homes()
    {
        return $this->hasMany(Home::class)->active();
    }
    # endregion
}
