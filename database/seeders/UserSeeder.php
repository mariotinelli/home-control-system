<?php

namespace Database\Seeders;

use App\Models\{Role, User};
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {

        $users = User::factory()->count(20)->create();

        $roles = Role::all();

        foreach ($users as $user) {
            $user->assignRole($roles->random());
        }

    }
}
