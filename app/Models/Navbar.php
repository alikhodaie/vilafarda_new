<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Navbar extends Model
{
    use HasFactory;

    # region Variables
    protected $guarded = [];

    protected $table = 'navbar';

    public $timestamps = true;
    # endregion

    # region Const
    const CACHE_KEY = 'navbar';
    # endregion

    # region Scopes
    public function scopeParent($query)
    {
        return $query->whereNull('parent_id');
    }
    # endregion

    # region Relations
    public function children()
    {
        return $this->hasMany(Navbar::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Navbar::class, 'parent_id');
    }
    # endregion
}
