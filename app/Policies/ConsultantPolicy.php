<?php

namespace App\Policies;

use App\Models\Consultant;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ConsultantPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function index(User $user): bool
    {
        return $user->hasPermissionTo('consultants:index');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('consultants:create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Consultant $consultant
     * @return Response|bool
     */
    public function update(User $user, Consultant $consultant): bool
    {
        return $user->hasPermissionTo('consultants:update');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Consultant $consultant
     * @return Response|bool
     */
    public function delete(User $user, Consultant $consultant): bool
    {
        return $user->hasPermissionTo('consultants:destroy');
    }
}
