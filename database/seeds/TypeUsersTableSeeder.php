<?php 

use Illuminate\Database\Seeder;

class TypeUsersTableSeeder extends Seeder {

    public function run() {
        
        \DB::table('type_users')->insert([
            'id' => 1,
            'name' => 'Super Administrador'
        ]);
        \DB::table('type_users')->insert([
            'id' => 2,
            'name' => 'Administrador'
        ]);
        \DB::table('type_users')->insert([
            'id' => 3,
            'name' => 'Contador'
        ]);
        \DB::table('type_users')->insert([
            'id' => 4,
            'name' => 'Cajero'
        ]);
        \DB::table('type_users')->insert([
            'id' => 5,
            'name' => 'Mesero'
        ]);
        \DB::table('type_users')->insert([
            'id' => 6,
            'name' => 'Cocina'
        ]);
    }

}
