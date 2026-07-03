<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\Workspace;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        $name = $this->faker->jobTitle();
        return [
            'id' => (string) Str::uuid(),
            'workspace_id' => Workspace::factory(),
            'company_id' => Company::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->sentence(),
            'status' => 'active',
            'is_system' => false,
        ];
    }
}
