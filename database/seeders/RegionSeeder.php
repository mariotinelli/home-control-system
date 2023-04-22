<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('regions')->insert([
            ['name' => 'Norte'],
            ['name' => 'Nordeste'],
            ['name' => 'Sudeste'],
            ['name' => 'Centro-Oeste'],
            ['name' => 'Sul']
        ]);
    }
}
