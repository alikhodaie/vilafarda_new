<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeVariable extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function variable()
    {
        return $this->belongsTo(Variable::class);
    }

    public function option()
    {
        return $this->belongsTo(VariableOption::class);
    }
}
