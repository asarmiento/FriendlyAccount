<?php 

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;

class TypeSeatsTableSeeder extends Seeder {

    public function run() {
        
        \DB::table('type_seats')->insert([
            'id' => 1,
            'abbreviation' => 'DGA',
            'name' => 'DIARIO GENERAL AUXILIAR',
            'quatity' => 1,
            'year' => 2015,
            'token' => Crypt::encrypt('DGA'),
            'school_id' => 1,
            'user_created' => 1,
            'user_updated' => NULL,
        ]);
        \DB::table('type_seats')->insert([
            'id' => 2,
            'abbreviation' => 'RCA',
            'name' => 'RECIBOS DE AUXILIAR',
            'quatity' => 1,
            'year' => 2015,
            'token' => Crypt::encrypt('RCA'),
            'school_id' => 1,
            'user_created' => 1,
            'user_updated' => NULL,
        ]);
        \DB::table('type_seats')->insert([
            'id' => 3,
            'abbreviation' => 'ALUMN',
            'name' => 'CARNET DE ALUMNOS',
            'quatity' => 1,
            'year' => 2015,
            'token' => Crypt::encrypt('ALUMN'),
            'school_id' => 1,
            'user_created' => 1,
            'user_updated' => NULL,
        ]);
        \DB::table('type_seats')->insert([
            'id' => 4,
            'abbreviation' => 'COMP',
            'name' => 'COMPRAS',
            'quatity' => 1,
            'year' => 2016,
            'token' => Crypt::encrypt('COMP'),
            'school_id' => 2,
            'user_created' => 1,
            'user_updated' => NULL,
        ]);
        \DB::table('type_seats')->insert([
            'id' => 5,
            'abbreviation' => 'CK',
            'name' => 'CHEQUES',
            'quatity' => 1,
            'year' => 2016,
            'token' => Crypt::encrypt('CK'),
            'school_id' => 2,
            'user_created' => 1,
            'user_updated' => NULL,
        ]);
        \DB::table('type_seats')->insert([
            'id' => 6,
            'abbreviation' => 'CK',
            'name' => 'CHEQUES',
            'quatity' => 1,
            'year' => 2016,
            'token' => Crypt::encrypt('CK'),
            'school_id' => 1,
            'user_created' => 1,
            'user_updated' => NULL,
        ]);
        \DB::table('type_seats')->insert([
            'id' => 7,
            'abbreviation' => 'RCA',
            'name' => 'RECIBOS DE AUXILIAR',
            'quatity' => 1,
            'year' => 2016,
            'token' => Crypt::encrypt('RCA'),
            'school_id' => 2,
            'user_created' => 1,
            'user_updated' => NULL,
        ]);
        \DB::table('type_seats')->insert([
            'id' => 8,
            'abbreviation' => 'ACOMP',
            'name' => 'AUXILIAR DE COMPRAS',
            'quatity' => 1,
            'year' => 2016,
            'token' => Crypt::encrypt('ACOMP'),
            'school_id' => 2,
            'user_created' => 1,
            'user_updated' => NULL,
        ]);
        \DB::table('type_seats')->insert([
            'id' => 9,
            'abbreviation' => 'CPA',
            'name' => 'COMPRAS AUXILIAR RESTAURANTE',
            'quatity' => 1,
            'year' => 2016,
            'token' => Crypt::encrypt('CPA'),
            'school_id' => 2,
            'user_created' => 1,
            'user_updated' => NULL,
        ]);
    }

}
