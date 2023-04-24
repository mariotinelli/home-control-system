<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('states')->insert([
            0 => [
                'name'      => 'Acre',
                'initials'  => 'AC',
                'region_id' => 1,
            ],
            1 => [
                'name'      => 'Alagoas',
                'initials'  => 'AL',
                'region_id' => 2,
            ],
            2 => [
                'name'      => 'Amazonas',
                'initials'  => 'AM',
                'region_id' => 1,
            ],
            3 => [
                'name'      => 'Amapá',
                'initials'  => 'AP',
                'region_id' => 1,
            ],
            4 => [
                'name'      => 'Bahia',
                'initials'  => 'BA',
                'region_id' => 2,
            ],
            5 => [
                'name'      => 'Ceará',
                'initials'  => 'CE',
                'region_id' => 2,
            ],
            6 => [
                'name'      => 'Distrito Federal',
                'initials'  => 'DF',
                'region_id' => 3,
            ],
            7 => [
                'name'      => 'Espírito Santo',
                'initials'  => 'ES',
                'region_id' => 4,
            ],
            8 => [
                'name'      => 'Goiás',
                'initials'  => 'GO',
                'region_id' => 3,
            ],
            9 => [

                'name'      => 'Maranhão',
                'initials'  => 'MA',
                'region_id' => 2,
            ],
            10 => [

                'name'      => 'Minas Gerais',
                'initials'  => 'MG',
                'region_id' => 4,
            ],
            11 => [

                'name'      => 'Mato Grosso do Sul',
                'initials'  => 'MS',
                'region_id' => 3,
            ],
            12 => [

                'name'      => 'Mato Grosso',
                'initials'  => 'MT',
                'region_id' => 3,
            ],
            13 => [

                'name'      => 'Pará',
                'initials'  => 'PA',
                'region_id' => 1,
            ],
            14 => [

                'name'      => 'Paraiba',
                'initials'  => 'PB',
                'region_id' => 2,
            ],
            15 => [

                'name'      => 'Pernambuco',
                'initials'  => 'PE',
                'region_id' => 2,
            ],
            16 => [

                'name'      => 'Piauí',
                'initials'  => 'PI',
                'region_id' => 2,
            ],
            17 => [

                'name'      => 'Paraná',
                'initials'  => 'PR',
                'region_id' => 5,
            ],
            18 => [

                'name'      => 'Rio de Janeiro',
                'initials'  => 'RJ',
                'region_id' => 4,
            ],
            19 => [

                'name'      => 'Rio Grande do Norte',
                'initials'  => 'RN',
                'region_id' => 2,
            ],
            20 => [

                'name'      => 'Rondônia',
                'initials'  => 'RO',
                'region_id' => 1,
            ],
            21 => [

                'name'      => 'Roraima',
                'initials'  => 'RR',
                'region_id' => 1,
            ],
            22 => [

                'name'      => 'Rio Grande do Sul',
                'initials'  => 'RS',
                'region_id' => 5,
            ],
            23 => [

                'name'      => 'Santa Catarina',
                'initials'  => 'SC',
                'region_id' => 5,
            ],
            24 => [

                'name'      => 'Sergipe',
                'initials'  => 'SE',
                'region_id' => 2,
            ],
            25 => [

                'name'      => 'São Paulo',
                'initials'  => 'SP',
                'region_id' => 4,
            ],
            26 => [

                'name'      => 'Tocantins',
                'initials'  => 'TO',
                'region_id' => 1,
            ],
        ]);
    }
}
