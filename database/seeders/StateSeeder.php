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
        DB::table('states')->insert(array (
            0 =>
            array (
                'name' => 'Acre',
                'initials' => 'AC',
                'region_id' => 1
            ),
            1 =>
            array (
                'name' => 'Alagoas',
                'initials' => 'AL',
                'region_id' => 2
            ),
            2 =>
            array (
                'name' => 'Amazonas',
                'initials' => 'AM',
                'region_id' => 1
            ),
            3 =>
            array (
                'name' => 'Amapá',
                'initials' => 'AP',
                'region_id' => 1
            ),
            4 =>
            array (
                'name' => 'Bahia',
                'initials' => 'BA',
                'region_id' => 2
            ),
            5 =>
            array (
                'name' => 'Ceará',
                'initials' => 'CE',
                'region_id' => 2
            ),
            6 =>
            array (
                'name' => 'Distrito Federal',
                'initials' => 'DF',
                'region_id' => 3
            ),
            7 =>
            array (
                'name' => 'Espírito Santo',
                'initials' => 'ES',
                'region_id' => 4
            ),
            8 =>
            array (
                'name' => 'Goiás',
                'initials' => 'GO',
                'region_id' => 3
            ),
            9 =>
            array (

                'name' => 'Maranhão',
                'initials' => 'MA',
                'region_id' => 2
            ),
            10 =>
            array (

                'name' => 'Minas Gerais',
                'initials' => 'MG',
                'region_id' => 4
            ),
            11 =>
            array (

                'name' => 'Mato Grosso do Sul',
                'initials' => 'MS',
                'region_id' => 3
            ),
            12 =>
            array (

                'name' => 'Mato Grosso',
                'initials' => 'MT',
                'region_id' => 3
            ),
            13 =>
            array (

                'name' => 'Pará',
                'initials' => 'PA',
                'region_id' => 1
            ),
            14 =>
            array (

                'name' => 'Paraiba',
                'initials' => 'PB',
                'region_id' => 2
            ),
            15 =>
            array (

                'name' => 'Pernambuco',
                'initials' => 'PE',
                'region_id' => 2
            ),
            16 =>
            array (

                'name' => 'Piauí',
                'initials' => 'PI',
                'region_id' => 2
            ),
            17 =>
            array (

                'name' => 'Paraná',
                'initials' => 'PR',
                'region_id' => 5
            ),
            18 =>
            array (

                'name' => 'Rio de Janeiro',
                'initials' => 'RJ',
                'region_id' => 4
            ),
            19 =>
            array (

                'name' => 'Rio Grande do Norte',
                'initials' => 'RN',
                'region_id' => 2
            ),
            20 =>
            array (

                'name' => 'Rondônia',
                'initials' => 'RO',
                'region_id' => 1
            ),
            21 =>
            array (

                'name' => 'Roraima',
                'initials' => 'RR',
                'region_id' => 1
            ),
            22 =>
            array (

                'name' => 'Rio Grande do Sul',
                'initials' => 'RS',
                'region_id' => 5
            ),
            23 =>
            array (

                'name' => 'Santa Catarina',
                'initials' => 'SC',
                'region_id' => 5
            ),
            24 =>
            array (

                'name' => 'Sergipe',
                'initials' => 'SE',
                'region_id' => 2
            ),
            25 =>
            array (

                'name' => 'São Paulo',
                'initials' => 'SP',
                'region_id' => 4
            ),
            26 =>
            array (

                'name' => 'Tocantins',
                'initials' => 'TO',
                'region_id' => 1
            ),
        ));
    }
}
