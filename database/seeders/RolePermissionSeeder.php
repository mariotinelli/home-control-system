<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $adminRole = \App\Models\Role::query()->create(['name' => RoleEnum::AD->value]);

        // Manager
        $managerRole = \App\Models\Role::query()->create(['name' => RoleEnum::M->value]);

        foreach (getManagerPermissions() as $permission) {

            $createdPermission = Permission::query()->firstOrCreate(['name' => $permission]);

            $managerRole->givePermissionTo($createdPermission);

            $createdPermission->assignRole($managerRole);
        }

        // User Gold
        $userGoldRole = \App\Models\Role::query()->create(['name' => RoleEnum::UO->value]);

        foreach (getUserGoldPermissions() as $permission) {

            $createdPermission = Permission::query()->firstOrCreate(['name' => $permission]);

            $userGoldRole->givePermissionTo($createdPermission);

            $createdPermission->assignRole($userGoldRole);

        }

        // User Silver
        $userSilverRole = \App\Models\Role::query()->create(['name' => RoleEnum::UP->value]);

        foreach (getUserSilverPermissions() as $permission) {

            $createdPermission = Permission::query()->firstOrCreate(['name' => $permission]);

            $userSilverRole->givePermissionTo($createdPermission);

            $createdPermission->assignRole($userSilverRole);

        }

        // User
        $userRole = \App\Models\Role::query()->create(['name' => RoleEnum::U->value]);

        foreach (getUserPermissions() as $permission) {

            $createdPermission = Permission::query()->firstOrCreate(['name' => $permission]);

            $userRole->givePermissionTo($createdPermission);

            $createdPermission->assignRole($userRole);

        }

    }
}
