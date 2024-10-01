<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use Illuminate\Database\Seeder;
/**
 * Description of UsersTableSeeder
 *
 * @author Anwar Sarmiento
 */
class UsersTableSeeder  extends Seeder{
    //put your code here
    public function run() {
        \DB::table('users')->insert([
            'id' => 1,
            'name' => 'Francisco',
            'last' => 'Gamonal',
            'email' => 'hfgamonalb@gmail.com',
            'password' => \Hash::make('123456'),
            'type_user_id' => 1,
            'token' => 'dds42rwsfw32ddsaf2r3qcd1321312312b56'
         ]);
        \DB::table('users')->insert([
            'id' => 2,
            'name' => 'Anwar',
            'last' => 'Sarmiento',
            'email' => 'anwarsarmiento@gmail.com',
            'password' => \Hash::make('F4cc0unt'),
            'type_user_id' => 1,
            'token' => 'dds42rwsfw32ddsaf2r3qcd1b56eqw233ewq'
         ]);
        \DB::table('users')->insert([
            'id' => 3,
            'name' => 'Karen',
            'last' => 'Ucles',
            'email' => 'kucles@ilcorso.com',
            'password' => \Hash::make('kucles'),
            'type_user_id' => 1,
            'token' => 'dds42rwsfw32ddsc<caf2rqara312312rrwr<xb52231213wqeqe6'
         ]);
        \DB::table('users')->insert([
            'id' => 4,
            'name' => 'Cajero',
            'last' => 'Caja',
            'email' => 'cajero@gmail.com',
            'password' => \Crypt::encrypt('cajero'),
            'type_user_id' => 4,
            'token' => 'dds42rwsfw32ddsc<12321zx<dwa3<xb56'
         ]);
        \DB::table('users')->insert([
            'id' => 5,
            'name' => 'Mesero',
            'last' => 'Salon',
            'email' => 'mesero@gmail.com',
            'password' => \Crypt::encrypt('mesero'),
            'type_user_id' => 5,
            'token' => 'dds42rwsfw32ddeqawrara312312rrwr<xb56'
         ]);
        /*\DB::table('users')->insert([
            'id' => 6,
            'name' => 'Cheff',
            'last' => 'Cocina',
            'email' => 'cheff@gmail.com',
            'password' => \Crypt::encrypt('cocina'),
            'type_user_id' => 6,
            'token' => 'dds42rwsfw32ddeqawrara3123132112rrwr<xb56'
         ]);*/
    }
}
