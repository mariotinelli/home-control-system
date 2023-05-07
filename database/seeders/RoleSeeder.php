<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (getRoles() as $role) {
            \App\Models\Role::query()->create([
                'name'       => $role,
                'guard_name' => 'web',
            ]);
        }
    }
}
