<?php

namespace App\Policies;

use App\Models\Chirp;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ChirpsPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function modify(User $user, Chirp $chirp): bool
    {
        return $chirp->user()->is($user);
    }
}
