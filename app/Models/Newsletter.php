<?php

namespace App\Models;

use App\Classes\Traits\PersianDate;
use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    # region traits
    use PersianDate;
    # endregion

    # region variables
    protected $guarded = [];
    # endregion

    # region Const
    const FILE_PATH = 'newsletter/';
    # endregion

    # region Methods
    public static function getDescriptionPath(): string
    {
        return self::FILE_PATH.'/description/';
    }
    # endregion
}
