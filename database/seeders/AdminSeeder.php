<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->create([
            'name'     => 'Admin',
            'email'    => 'admin@admin.com',
            'password' => Hash::make('12345678'),
        ]);

        $user->assignRole('Administrador');
        $user->givePermissionTo(getAdminPermissions());
    }
}
