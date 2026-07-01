<?php

namespace Tests\Unit;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RbacTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_permission(): void
    {
        $user = User::factory()->create();
        $role = Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $permission = Permission::create([
            'name' => 'View Users',
            'slug' => 'users.view',
            'group' => 'Identity',
        ]);

        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $this->assertTrue($user->hasPermission('users.view'));
        $this->assertFalse($user->hasPermission('users.edit'));
    }
}
