<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HomeDailyStat extends Model
{
    protected $fillable = [
        'home_id',
        'stat_date',
        'views',
        'clicks',
        'income',
    ];

    protected $casts = [
        'stat_date' => 'date',
        'views' => 'integer',
        'clicks' => 'integer',
        'income' => 'integer',
    ];

    public function home(): BelongsTo
    {
        return $this->belongsTo(Home::class);
    }
}
