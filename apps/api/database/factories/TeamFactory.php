<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\Department;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Workspace;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TeamFactory extends Factory
{
    protected $model = Team::class;

    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'workspace_id' => Workspace::factory(),
            'company_id' => Company::factory(),
            'branch_id' => Branch::factory(),
            'department_id' => Department::factory(),
            'name' => $this->faker->word() . ' Team',
            'is_active' => true,
        ];
    }
}
