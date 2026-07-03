<?php

namespace Tests\Feature\Organization;

use App\Domains\Organization\Tenant\TenantContext;
use App\Models\Company;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class WorkspaceIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_workspace_context_is_resolved_from_authenticated_user()
    {
        Route::middleware(['api', 'auth:sanctum', 'tenant'])
            ->get('/test-workspace', function () {
                $context = app(TenantContext::class);
                return response()->json([
                    'workspace_id' => $context->getWorkspaceId(),
                    'auth_user_id' => auth()->id(),
                    'auth_workspace_id' => auth()->user()?->workspace_id,
                ]);
            });

        $workspace = Workspace::factory()->create();
        $user = User::factory()->create([
            'workspace_id' => $workspace->id,
        ]);

        $response = $this->actingAs($user)->getJson('/test-workspace');

        $response->assertStatus(200);
        $data = $response->json();

        // Debugging output
        if ($data['workspace_id'] === null) {
            dump($data);
        }

        $this->assertEquals($user->id, $data['auth_user_id']);
        $this->assertEquals($workspace->id, $data['auth_workspace_id']);
        $this->assertEquals($workspace->id, $data['workspace_id']);
    }

    public function test_workspace_middleware_prevents_access_without_workspace()
    {
        Route::middleware(['api', 'auth:sanctum', 'tenant', 'workspace'])
            ->get('/test-workspace-only', function () {
                return response()->json(['message' => 'Success']);
            });

        $userWithoutWorkspace = User::factory()->create(['workspace_id' => null]);

        $response = $this->actingAs($userWithoutWorkspace)->getJson('/test-workspace-only');

        $response->assertStatus(403)
            ->assertJson(['message' => 'Unauthorized. No workspace context found.']);
    }
}
