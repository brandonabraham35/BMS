<?php

namespace App\Domains\AccessControl\Services;

use App\Models\Role;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class RoleService
{
    public function __construct(protected AuditLogger $auditLogger) {}

    public function list(Request $request): LengthAwarePaginator
    {
        return Role::search($request, ['name', 'slug'])
            ->where('workspace_id', $request->user()->workspace_id)
            ->paginate($request->input('per_page', 15));
    }

    public function create(array $data): Role
    {
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $role = Role::create($data);

        if (!empty($data['permissions'])) {
            $role->permissions()->sync($data['permissions']);
        }

        $this->auditLogger->log('role.created', $role, null, $role->toArray());

        return $role;
    }

    public function update(Role $role, array $data): Role
    {
        $oldValues = $role->toArray();
        $role->update($data);

        if (isset($data['permissions'])) {
            $role->permissions()->sync($data['permissions']);
        }

        $this->auditLogger->log('role.updated', $role, $oldValues, $role->toArray());

        return $role;
    }

    public function clone(Role $role, string $newName): Role
    {
        $newRole = $role->replicate(['is_system']);
        $newRole->name = $newName;
        $newRole->slug = Str::slug($newName);
        $newRole->save();

        $newRole->permissions()->sync($role->permissions->pluck('id'));

        $this->auditLogger->log('role.cloned', $newRole, ['original_id' => $role->id]);

        return $newRole;
    }
}
