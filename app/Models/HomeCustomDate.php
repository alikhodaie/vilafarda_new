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
        'date' => 'date',
        'is_active' => 'boolean',
    ];

    public function home()
    {
        return $this->belongsTo(Home::class);
    }

    public function isUnavailable(): bool
    {
        return $this->is_active === false || (int) $this->price === 0;
    }

    public function scopeUnavailable($query)
    {
        return $query->where(function ($inner) {
            $inner->where('is_active', false)
                ->orWhere('price', 0);
        });
    }
}
