<?php


namespace App\Classes\Traits;


use Morilog\Jalali\Jalalian;

trait PersianDate
{
    public function persianCreatedAt($format = "%d %B %Y ساعت H:i")
    {
        return $this->persianDate('created_at', $format);
    }

    public function persianUpdatedAt($format = "%d %B %Y ساعت H:i")
    {
        return $this->persianDate('updated_at', $format);
    }

    public function persianDate($field ,$format = "%d %B %Y ساعت H:i")
    {
        $date = $this->$field;
        return persianDate($date->timezone(config()->get('app.timezone')))->format($format);
    }
}
