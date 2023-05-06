<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $adminRole = \App\Models\Role::query()->create(['name' => 'Administrador']);

        foreach (getAdminPermissions() as $permission) {

            $createdPermission = Permission::query()->firstOrCreate(['name' => $permission]);

            $adminRole->givePermissionTo($createdPermission);

            $createdPermission->assignRole($adminRole);

        }

        // User Gold
        $userGoldRole = \App\Models\Role::query()->create(['name' => 'Usuário Ouro']);

        foreach (getAdminPermissions() as $permission) {

            $createdPermission = Permission::query()->firstOrCreate(['name' => $permission]);

            $userGoldRole->givePermissionTo($createdPermission);

            $createdPermission->assignRole($userGoldRole);

        }

        // User Silver
        $userSilverRole = \App\Models\Role::query()->create(['name' => 'Usuário Prata']);

        foreach (getAdminPermissions() as $permission) {

            $createdPermission = Permission::query()->firstOrCreate(['name' => $permission]);

            $userSilverRole->givePermissionTo($createdPermission);

            $createdPermission->assignRole($userSilverRole);

        }

        // User
        $userRole = \App\Models\Role::query()->create(['name' => 'Usuário']);

        foreach (getAdminPermissions() as $permission) {

            $createdPermission = Permission::query()->firstOrCreate(['name' => $permission]);

            $userRole->givePermissionTo($createdPermission);

            $createdPermission->assignRole($userRole);

        }

    }
}
