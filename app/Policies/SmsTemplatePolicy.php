<?php

namespace App\Policies;

use App\Models\User;
use App\Support\SmsTemplates;
use Illuminate\Auth\Access\HandlesAuthorization;

class SmsTemplatePolicy
{
    use HandlesAuthorization;

    public function index(User $user): bool
    {
        return $user->checkPermissionTo('sms-templates:index');
    }
}
