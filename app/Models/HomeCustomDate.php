<?php

namespace App\Models;

use App\Classes\Traits\PersianDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeCustomDate extends Model
{
    use HasFactory, PersianDate;

    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'date' => 'date'
    ];

    public function home()
    {
        return $this->belongsTo(Home::class);
    }
}
