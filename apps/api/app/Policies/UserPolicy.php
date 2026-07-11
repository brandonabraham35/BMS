<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function view(User $user, User $model): bool
    {
        return (string) $user->workspace_id === (string) $model->workspace_id;
    }

    public function update(User $user, User $model): bool
    {
        return (string) $user->workspace_id === (string) $model->workspace_id;
    }

    public function delete(User $user, User $model): bool
    {
        return (string) $user->workspace_id === (string) $model->workspace_id && $user->id !== $model->id;
    }
}
