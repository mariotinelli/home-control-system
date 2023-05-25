<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;

class PermissionTestUserSeeder extends Seeder
{
    public function run(): void
    {

        User::factory()->create([
            'name'     => 'Manager',
            'email'    => 'manager@email.com',
            'password' => \Hash::make('12345678'),
        ])->assignRole(RoleEnum::M->value);

        User::factory()->create([
            'name'     => 'Usuário Ouro',
            'email'    => 'usuario-ouro@email.com',
            'password' => \Hash::make('12345678'),
        ])->assignRole(RoleEnum::UO->value);
        ;

        User::factory()->create([
            'name'     => 'Usuário Prata',
            'email'    => 'usuario-prata@email.com',
            'password' => \Hash::make('12345678'),
        ])->assignRole(RoleEnum::UP->value);
        ;

        User::factory()->create([
            'name'     => 'Usuário',
            'email'    => 'usuario@email.com',
            'password' => \Hash::make('12345678'),
        ])->assignRole(RoleEnum::U->value);
        ;

    }
}
