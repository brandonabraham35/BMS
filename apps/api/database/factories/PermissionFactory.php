<?php

namespace Database\Factories;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PermissionFactory extends Factory
{
    protected $model = Permission::class;

    public function definition(): array
    {
        $action = $this->faker->randomElement(['view', 'create', 'update', 'delete']);
        $module = $this->faker->word();
        return [
            'id' => (string) Str::uuid(),
            'name' => ucfirst($action) . ' ' . ucfirst($module),
            'slug' => $module . '.' . $action,
            'group' => ucfirst($module),
        ];
    }
}
