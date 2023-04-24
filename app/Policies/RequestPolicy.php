<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Request;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RequestPolicy
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

    public function approve(User $user, Request $request): bool
    {
        return $user->role === UserRole::ADMIN;
    }
}
