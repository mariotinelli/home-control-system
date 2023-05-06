<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {

        foreach (getAdminPermissions() as $permission) {
            \App\Models\Permission::query()->create([
                'name'       => $permission,
                'guard_name' => 'web',
            ]);
        }

    }
}
