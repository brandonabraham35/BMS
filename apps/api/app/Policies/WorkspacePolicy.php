<?php

namespace App\Policies;

use App\Models\Workspace;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorkspacePolicy
{
    use HandlesAuthorization;

    public function view(User $user, Workspace $workspace): bool
    {
        return $user->workspace_id === $workspace->id;
    }

    public function update(User $user, Workspace $workspace): bool
    {
        return $user->workspace_id === $workspace->id;
    }

    public function delete(User $user, Workspace $workspace): bool
    {
        return $user->workspace_id === $workspace->id;
    }
}
