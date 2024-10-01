<?php

use Illuminate\Database\Seeder;

class SchoolsTableSeeder extends Seeder {

    public function run() {
        \DB::table('schools')->insert([
            'id' => 1,
            'name' => 'ESCUELA ADVENTISTA DE VALLE DE ANGELES',
            'charter' => '3-008-056720',
            'phoneOne' => '0000-0000',
            'phoneTwo' => '0000-0000',
            'fax' => '0000-0000',
            'address' => 'ENTRADA AL HOSPITAL ADVENTISTA',
            'town' => 'VALLE DE ANGELES',
            'token' => Crypt::encrypt('COLEGIO TECNICO PROFESIONAL DE QUEPOS')
        ]);
        \DB::table('schools')->insert([
            'id' => 2,
            'name' => 'MINI SUPER DANNA',
            'charter' => '134-000267520',
            'phoneOne' => '0000-0000',
            'phoneTwo' => '0000-0000',
            'fax' => '0000-0000',
            'address' => 'BARRIO SAN MARTIN PRIMERA ENTRADA',
            'town' => 'QUEPOS',
            'token' => Crypt::encrypt('DANNA')
        ]);
    }

}
