<?php

namespace App\Domains\AccessControl\Services;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class PermissionResolver
{
    public function resolve(User $user): Collection
    {
        $cacheKey = "user_permissions_{$user->id}";

        return Cache::remember($cacheKey, now()->addMinutes(60), function () use ($user) {
            $permissions = collect();

            // 1. Roles Permissions
            foreach ($user->roles as $role) {
                if ($role->status === 'active') {
                    $permissions = $permissions->merge($role->permissions->pluck('slug'));
                }
            }

            // 2. Direct User Permissions (Overrides)
            $directPermissions = $user->directPermissions()
                ->where(function ($query) {
                    $query->whereNull('expires_at')
                        ->orWhere('expires_at', '>', now());
                })
                ->get()
                ->pluck('slug');

            $permissions = $permissions->merge($directPermissions);

            return $permissions->unique()->values();
        });
    }

    public function invalidate(User $user): void
    {
        Cache::forget("user_permissions_{$user->id}");
    }
}
