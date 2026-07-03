<?php

namespace Tests\Feature\AccessControl;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessControlApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Workspace $workspace;

    protected function setUp(): void
    {
        parent::setUp();
        $this->workspace = Workspace::factory()->create();
        $this->user = User::factory()->create(['workspace_id' => $this->workspace->id]);
    }

    public function test_can_list_roles()
    {
        Role::factory()->count(3)->create(['workspace_id' => $this->workspace->id]);

        $response = $this->actingAs($this->user)->getJson('/api/v1/roles');

        $response->assertStatus(200);
        $this->assertGreaterThanOrEqual(3, count($response->json('data')));
    }

    public function test_can_create_role_with_permissions()
    {
        $permissions = Permission::factory()->count(2)->create();

        $data = [
            'name' => 'Custom Manager',
            'permissions' => $permissions->pluck('id')->toArray()
        ];

        $response = $this->actingAs($this->user)->postJson('/api/v1/roles', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('roles', ['name' => 'Custom Manager']);
    }

    public function test_permission_resolution_works()
    {
        $permission = Permission::factory()->create(['slug' => 'inventory.view']);
        $role = Role::factory()->create(['workspace_id' => $this->workspace->id]);
        $role->permissions()->attach($permission);

        $this->user->roles()->attach($role);

        $this->assertTrue($this->user->hasPermission('inventory.view'));
    }
}
