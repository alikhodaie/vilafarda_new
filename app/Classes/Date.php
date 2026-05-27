<?php


namespace App\Classes;


use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Morilog\Jalali\CalendarUtils;
use Morilog\Jalali\Jalalian;
use GeniusTS\HijriDate\Date as HijriDate;
use GeniusTS\HijriDate\Hijri;

class Date
{
    const JALALI_HOLIDAY_DATES = [
        '01/01',
        '01/02',
        '01/03',
        '01/04',
        '01/12',
        '01/13',
        '03/14',
        '03/15',
        '11/22',
        '12/29',
        '12/30',
    ];

    const HIJRI_HOLIDAY_DATES = [
        '01/09',
        '01/10',
        '02/20',
        '02/28',
        '02/30',
        '03/08',
        '03/17',
        '06/03',
        '07/14',
        '07/28',
        '08/15',
        '09/21',
        '10/01',
        '10/02',
        '10/25',
        '12/10',
        '12/18',
    ];

    const HIJRI_ISSUE_DATES = [
        '10/25',
        '01/10',
        '01/09',
        '12/10', // عید قربان — یک روز جلوتر از تبدیل خام قمری، مطابق تقویم رسمی ایران
    ];

    public static function holidayList(): Collection
    {
        $jalali = collect(self::JALALI_HOLIDAY_DATES)->map(function ($date){
            return self::jalaliToGregorian($date)->format('Y-m-d');
        });

        $hijri = collect(self::HIJRI_HOLIDAY_DATES)->map(function ($date){
            return self::hijriToGregorian($date)->format('Y-m-d');
        });

        return $jalali->merge($hijri)->unique()->sort();
    }

    public static function isHoliday(Carbon $date): bool
    {
        if (in_array(self::toJalali($date)->format('m/d'), self::JALALI_HOLIDAY_DATES)){
            return true;
        }

        $gregorian = $date->format('Y-m-d');
        foreach (self::HIJRI_HOLIDAY_DATES as $hijri_date) {
            if (self::hijriToGregorian($hijri_date)->format('Y-m-d') === $gregorian) {
                return true;
            }
        }

        return false;
    }

    public static function toJalali(Carbon $date): Jalalian
    {
        return Jalalian::fromCarbon($date);
    }

    public static function toHijri(Carbon $date): Carbon
    {
        $date = Carbon::parse(Hijri::convertToHijri($date));

        $issue_dates = [];
        foreach (self::HIJRI_ISSUE_DATES as $issue_date){
            [$month, $day] = explode('/', $issue_date);
            $year = HijriDate::now()->format('Y');

            $issue_dates[] = Carbon::parse("$year/$month/$day")
                ->addDay()
                ->format('m/d');
        }

        if (in_array($date->format('m/d'), $issue_dates)) {
            $date->subDay();
        }
        return $date;
    }

    public static function jalaliToGregorian(string $date, $separator = '/'): Carbon
    {
        $date = explode($separator, $date);
        $now = Jalalian::now();
        $index = 0;

        $year = (count($date) === 3) ? $date[$index++]: $now->format('Y');
        $month = (count($date) === 2 || $index === 1) ? $date[$index++]: $now->format('m');
        $day = (count($date) === 1 || $index === 1) ? $date[$index]: $now->format('d');

        $date = CalendarUtils::toGregorian($year, $month, $day);
        $date = Carbon::parse(implode('/', $date));

        if ($date->isPast())
        {
            $year++;
            return self::jalaliToGregorian("$year/$month/$day");
        }

        return $date;
    }

    public static function hijriToGregorian(string $date, $separator = '/'): Carbon
    {
        $date = explode($separator, $date);
        $index = 0;

        if (count($date) !== 2 && count($date) !== 3) {
            throw new Exception('Invalid Hijri date format');
        }

        $year = (count($date) === 3) ? $date[$index++]: HijriDate::now()->format('Y');
        $month = (count($date) === 2 || $index === 1) ? $date[$index++]:  HijriDate::now()->format('m');
        $day = (count($date) === 1 || $index >= 1) ? $date[$index]: HijriDate::now()->format('d');

        $date = Hijri::convertToGregorian((int)$day, (int)$month, (int)$year);
        $date = Carbon::parse($date);

        if ($date->isPast())
        {
            $year++;
            return self::hijriToGregorian("$year/$month/$day");
        }

        if (in_array("$month/$day", self::HIJRI_ISSUE_DATES)){
            $date->addDay();
        }

        return $date;
    }
}
