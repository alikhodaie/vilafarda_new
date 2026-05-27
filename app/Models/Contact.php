<?php

namespace App\Models;

use App\Classes\Traits\PersianDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory, PersianDate;

    protected $guarded = [];

    protected $casts = [
        'is_saw' => true
    ];

    # region Methods

    /**
     * Update specific contact to saw
     *
     * @return $this
     */
    public function seen(): Contact
    {
        if (!$this->is_seen){
            $this->update(['is_seen' => true]);
        }
        return $this;
    }
    # endregion
}
