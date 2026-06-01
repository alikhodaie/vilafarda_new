<?php

namespace App\Policies;

use App\Models\SmsLog;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SmsLogPolicy
{
    use HandlesAuthorization;

    public function index(User $user): bool
    {
        return $user->checkPermissionTo('sms-logs:index');
    }

    public function show(User $user, SmsLog $smsLog): bool
    {
        return $user->checkPermissionTo('sms-logs:index');
    }
}
