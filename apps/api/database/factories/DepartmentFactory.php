<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Workspace;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'workspace_id' => Workspace::factory(),
            'company_id' => Company::factory(),
            'branch_id' => Branch::factory(),
            'name' => $this->faker->jobTitle() . ' Department',
            'code' => strtoupper($this->faker->lexify('???')),
            'is_active' => true,
        ];
    }
}
