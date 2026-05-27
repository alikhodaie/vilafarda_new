<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationToken extends Model
{
    use HasFactory;

    # region constants
    const MAX_ATTEMPT_IN_DAY = 3;
    const EXPIRE_MINUTES = 5;

    const RESET_PASSWORD = 'reset-password';
    const LOGIN = 'login';

    const TYPES = [
        self::RESET_PASSWORD,
        self::LOGIN
    ];
    # endregion

    # region variables

    protected $guarded = [];

    public $timestamps = false;

    public $casts = [
        'created_at' => 'datetime',
        'expired_at' => 'datetime'
    ];

    # endregion

    # region methods
    public static function generateToken($value, $type, $length = 6): VerificationToken
    {
        $valid_token = self::validToken($value, $type);
        if (!$valid_token) {
            $valid_token = self::query()->create([
                'type' => $type,
                'value' => $value,
                'token' => self::randomNumber($length),
                'created_at' => Carbon::now(),
                'expired_at' => Carbon::now()->addMinutes(self::EXPIRE_MINUTES)
            ]);

            $valid_token->fresh = true;
        }


        return $valid_token;
    }

    public static function randomNumber($length)
    {
        $start = '1';
        $end = '9';
        for ($i = 1; $i < $length; $i++){
            $start .= '0';
            $end .= '9';
        }

        return rand(intval($start), intval($end));
    }

    public static function validToken($value, $type): ?VerificationToken
    {
        return self::query()
            ->where('type', $type)
            ->where('value', $value)
            ->where('expired_at', '>', Carbon::now())
            ->latest('created_at')
            ->first();
    }

    public static function tooManyAttempt($value, $type): bool
    {
        $attempt = self::query()
            ->where('type', $type)
            ->where('value', $value)
            ->whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])
            ->where('expired_at', '<', Carbon::now())
            ->count();

        if ($attempt < self::MAX_ATTEMPT_IN_DAY) {
            return false;
        }

        return true;
    }
    # endregion

}
