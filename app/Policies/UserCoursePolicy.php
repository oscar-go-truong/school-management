<?php

namespace App\Policies;

use App\Enums\UserRoleContants;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserCoursePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function create(User $user): bool
    {
        return $user->role === UserRoleContants::ADMIN;
    }

    public function update(User $user): bool
    {
        return $user->role === UserRoleContants::ADMIN;
    }
}
