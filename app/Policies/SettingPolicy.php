<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Models\Permission;

class SettingPolicy
{
    use HandlesAuthorization;

    private function userHasPermission(User $user, string $permission): bool
    {
        if (! Permission::query()->where('name', $permission)->where('guard_name', 'web')->exists()) {
            return false;
        }

        return $user->hasPermissionTo($permission);
    }

    public function index(User $user): bool
    {
        return $this->contactUs($user) ||
                $this->privacy($user) ||
                $this->indexPage($user) ||
                $this->faq($user) ||
                $this->commission($user) ||
                $this->rejectPolicy($user) ||
                $this->footer($user) ||
                $this->aboutUs($user) ||
                $this->submitHome($user) ||
                $this->general($user) ||
                $this->seo($user);
    }

    public function general(User $user): bool
    {
        return $this->logo($user) ||
                $this->appModalAuth($user) ||
                $this->appContact($user) ||
                $this->appNewsletter($user);
    }

    public function submitHome(User $user): bool
    {
        return $user->hasPermissionTo('setting:submit-home');
    }

    public function commission(User $user): bool
    {
        return $user->hasPermissionTo('setting:commission');
    }

    public function rejectPolicy(User $user): bool
    {
        return $user->hasPermissionTo('setting:reject-policy');
    }

    public function logo(User $user): bool
    {
        return $user->hasPermissionTo('setting:app-logo');
    }

    public function appModalAuth(User $user): bool
    {
        return $user->hasPermissionTo('setting:app-modal-auth');
    }

    public function appContact(User $user): bool
    {
        return $user->hasPermissionTo('setting:app-contact');
    }

    public function appNewsletter(User $user): bool
    {
        return $user->hasPermissionTo('setting:app-newsletter');
    }

    public function indexPage(User $user): bool
    {
        return $user->hasPermissionTo('setting:index');
    }

    public function contactUs(User $user): bool
    {
        return $user->hasPermissionTo('setting:contact-us');
    }

    public function privacy(User $user): bool
    {
        return $user->hasPermissionTo('setting:privacy');
    }

    public function aboutUs(User $user): bool
    {
        return $user->hasPermissionTo('setting:about-us');
    }

    public function faq(User $user): bool
    {
        return $user->hasPermissionTo('setting:faq');
    }

    public function footer(User $user): bool
    {
        return $user->hasPermissionTo('setting:footer');
    }

    public function payment(User $user): bool
    {
        return $user->hasPermissionTo('setting:payment');
    }

    public function seo(User $user): bool
    {
        return $this->userHasPermission($user, 'setting:seo');
    }
}
