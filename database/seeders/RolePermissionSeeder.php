<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $role = \App\Models\Role::query()->whereName('Administrador')->first();

        foreach (getAdminPermissions() as $permission) {
            $role->givePermissionTo($permission);
        }

        // User Gold
        $role = \App\Models\Role::query()->whereName('Usuário Ouro')->first();

        foreach (getUserGoldPermissions() as $permission) {
            $role->givePermissionTo($permission);
        }

        // User Silver
        $role = \App\Models\Role::query()->whereName('Usuário Prata')->first();

        foreach (getUserSilverPermissions() as $permission) {
            $role->givePermissionTo($permission);
        }

        // User
        $role = \App\Models\Role::query()->whereName('Usuário')->first();

        foreach (getUserPermissions() as $permission) {
            $role->givePermissionTo($permission);
        }

    }
}
