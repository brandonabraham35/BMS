<?php

namespace Tests\Feature\Organization;

use App\Models\Company;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyApiTest extends TestCase
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

    public function test_can_list_companies()
    {
        // Delete any companies created by the user factory
        Company::where('workspace_id', $this->workspace->id)->delete();

        Company::factory()->count(3)->create(['workspace_id' => $this->workspace->id]);

        $response = $this->actingAs($this->user)->getJson('/api/v1/companies');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_can_create_company()
    {
        $data = [
            'name' => 'New Company',
            'legal_name' => 'New Company Ltd',
            'email' => 'contact@newcompany.com',
            'currency' => 'USD',
        ];

        $response = $this->actingAs($this->user)->postJson('/api/v1/companies', $data);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'New Company');

        $this->assertDatabaseHas('companies', [
            'name' => 'New Company',
            'workspace_id' => $this->workspace->id
        ]);
    }
}
