<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::create([
            'name' => 'BMS Enterprise Ltd',
            'slug' => 'bms-enterprise',
            'email' => 'admin@bms.com',
        ]);

        $branch = Branch::create([
            'company_id' => $company->id,
            'name' => 'Main Branch',
        ]);

        $adminRole = Role::create([
            'company_id' => $company->id,
            'name' => 'Administrator',
            'slug' => 'admin',
        ]);

        $user = User::create([
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'name' => 'System Admin',
            'email' => 'admin@bms.com',
            'password' => Hash::make('password'),
        ]);

        $user->roles()->attach($adminRole);
    }
}
