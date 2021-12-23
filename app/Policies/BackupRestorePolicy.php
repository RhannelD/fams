<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BackupRestorePolicy
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

    public function viewAny(User $user)
    {
        return $user->is_admin();
    }

    public function create(User $user)
    {
        return $user->is_admin();
    }

    public function download(User $user)
    {
        return $user->is_admin();
    }

    public function upload(User $user)
    {
        return $user->is_admin();
    }

    public function restore(User $user)
    {
        return $user->is_admin();
    }

    public function delete(User $user)
    {
        return $user->is_admin();
    }
}
