<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;

class CatalogsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('catalogs')->insert([
            'id'=>1,
            'code' => '01-00-00-00-000',
            'name' => 'ACTIVOS',
            'style' => 'Grupo',
            'note' => false,
            'type' => 1,
            'level' => '1',
            'school_id' => 2,
            'user_created' => 2,
            'token' => Crypt::encrypt('activos'),
            'catalog_id' => NULL
       ]);
        \DB::table('catalogs')->insert([
            'id'=>2,
            'code' => '01-01-00-00-000',
            'name' => 'ACTIVOS CORRIENTES',
            'style' => 'Grupo',
            'note' => false,
            'type' => 1,
            'level' => '2',
            'school_id' => 2,
            'user_created' => 2,
            'token' => Crypt::encrypt('CORRIENTE'),
            'catalog_id' => 1
        ]);
        \DB::table('catalogs')->insert([
            'id'=>3,
            'code' => '01-01-01-00-000',
            'name' => 'CAJA Y BANCOS',
            'style' => 'Grupo',
            'note' => false,
            'type' => 1,
            'level' => '3',
            'school_id' => 2,
            'user_created' => 2,
            'token' => Crypt::encrypt('CORRIENTE'),
            'catalog_id' => 1
        ]);
        \DB::table('catalogs')->insert([
            'id'=>4,
            'code' => '01-01-01-01-000',
            'name' => 'CAJA',
            'style' => 'Grupo',
            'note' => false,
            'type' => 1,
            'level' => '4',
            'school_id' => 2,
            'user_created' => 2,
            'token' => Crypt::encrypt('caja'),
            'catalog_id' => 3
        ]);
        \DB::table('catalogs')->insert([
            'id'=>5,
            'code' => '01-01-01-01-001',
            'name' => 'EFECTIVO',
            'style' => 'Detalle',
            'note' => false,
            'type' => 1,
            'level' => '5',
            'school_id' => 2,
            'user_created' => 2,
            'token' => Crypt::encrypt('efectivo'),
            'catalog_id' => 4
        ]);
        \DB::table('catalogs')->insert([
            'id'=>6,
            'code' => '01-01-01-02-000',
            'name' => 'BANCOS',
            'style' => 'Grupo',
            'note' => false,
            'type' => 1,
            'level' => '4',
            'school_id' => 2,
            'user_created' => 2,
            'token' => Crypt::encrypt('bancos'),
            'catalog_id' => 3
        ]);
        \DB::table('catalogs')->insert([
            'id'=>7,
            'code' => '01-02-00-00-000',
            'name' => 'PROPIEDADES PLANTA Y EQUIPO',
            'style' => 'Grupo',
            'note' => false,
            'type' => 1,
            'level' => '2',
            'school_id' => 2,
            'user_created' => 2,
            'token' => Crypt::encrypt('PLANTA'),
            'catalog_id' => 1
        ]);
        \DB::table('catalogs')->insert([
            'id'=>8,
            'code' => '01-03-00-00-000',
            'name' => 'DIFERIDOS',
            'style' => 'Grupo',
            'note' => false,
            'type' => 1,
            'level' => '2',
            'school_id' => 2,
            'user_created' => 2,
            'token' => Crypt::encrypt('DIFERIDO'),
            'catalog_id' => 1
        ]);
        \DB::table('catalogs')->insert([
            'id'=>9,
            'code' => '01-04-00-00-000',
            'name' => 'OTROS ACTIVOS',
            'style' => 'Grupo',
            'note' => false,
            'type' => 1,
            'level' => '2',
            'school_id' => 2,
            'user_created' => 2,
            'token' => Crypt::encrypt('OTROS'),
            'catalog_id' => 1
        ]);

        \DB::table('catalogs')->insert([
            'id'=>10,
            'code' => '02-00-00-00-000',
            'name' => 'PASIVOS',
            'style' => 'Grupo',
            'note' => false,
            'type' => 2,
            'level' => '1',
            'school_id' => 2,
            'user_created' => 2,
            'token' => Crypt::encrypt('pasivos'),
            'catalog_id' => NULL
        ]);
        \DB::table('catalogs')->insert([
            'id'=>11,
            'code' => '02-01-00-00-000',
            'name' => 'PASIVOS A CORTO PLAZO',
            'style' => 'Grupo',
            'note' => false,
            'type' => 2,
            'level' => '2',
            'school_id' => 2,
            'user_created' => 2,
            'token' => Crypt::encrypt('CORTO'),
            'catalog_id' => 10
        ]);
        \DB::table('catalogs')->insert([
            'id'=>12,
            'code' => '02-01-01-00-000',
            'name' => 'CONTROL PROVEEDORES X PAGAR',
            'style' => 'Detalle',
            'note' => false,
            'type' => 2,
            'level' => '3',
            'school_id' => 2,
            'user_created' => 2,
            'token' => Crypt::encrypt('control'),
            'catalog_id' => 11
        ]);
        \DB::table('catalogs')->insert([
            'id'=>25,
            'code' => '02-01-02-00-000',
            'name' => 'IMPUESTOS DE VENTAS',
            'style' => 'Detalle',
            'note' => false,
            'type' => 2,
            'level' => '3',
            'school_id' => 2,
            'user_created' => 2,
            'token' => Crypt::encrypt('conStrol'),
            'catalog_id' => 11
        ]);
        \DB::table('catalogs')->insert([
            'id'=>26,
            'code' => '02-01-03-00-000',
            'name' => 'IMPUESTOS DE RENTA',
            'style' => 'Detalle',
            'note' => false,
            'type' => 2,
            'level' => '3',
            'school_id' => 2,
            'user_created' => 2,
            'token' => Crypt::encrypt('coDntrol'),
            'catalog_id' => 11
        ]);
        \DB::table('catalogs')->insert([
            'id'=>13,
            'code' => '02-02-00-00-000',
            'name' => 'PASIVOS A LARGO PLAZO',
            'style' => 'Grupo',
            'note' => false,
            'type' => 2,
            'level' => '2',
            'school_id' => 2,
            'user_created' => 2,
            'token' => Crypt::encrypt('OTROS'),
            'catalog_id' => 10
        ]);
        \DB::table('catalogs')->insert([
            'id'=>14,
            'code' => '03-00-00-00-000',
            'name' => 'CAPITAL O PATRIMONIO',
            'style' => 'Grupo',
            'note' => false,
            'type' => 3,
            'level' => '1',
            'school_id' => 2,
            'user_created' => 2,
            'token' => Crypt::encrypt('capital'),
            'catalog_id' => NULL
        ]);
        \DB::table('catalogs')->insert([
            'id' => 15,
            'code' => '03-01-00-00-000',
            'name' => 'FONDOS DISPONIBLES',
            'style' => 'Detalle',
            'note' => false,
            'type' => 3,
            'level' => '2',
            'school_id' => 2,
            'user_created' => 2,
            'token' => Crypt::encrypt('OTROS'),
            'catalog_id' => 14
        ]);
        \DB::table('catalogs')->insert([
            'id'=>16,
            'code' => '04-00-00-00-000',
            'name' => 'INGRESOS',
            'style' => 'Grupo',
            'note' => false,
            'type' => 4,
            'level' => '1',
            'school_id' => 2,
            'user_created' => 2,
            'token' => Crypt::encrypt('ingresos'),
            'catalog_id' => NULL
        ]);

        \DB::table('catalogs')->insert([
            'id'=>17,
            'code' => '05-00-00-00-000',
            'name' => 'COMPRAS',
            'style' => 'Grupo',
            'note' => false,
            'type' => 5,
            'level' => '1',
            'school_id' => 2,
            'user_created' => 2,
            'token' => Crypt::encrypt('compras'),
            'catalog_id' => NULL
        ]);

        \DB::table('catalogs')->insert([
            'id'=>18,
            'code' => '05-01-00-00-000',
            'name' => 'COMPRAS CONTROL PROVEEDORES',
            'style' => 'Detalle',
            'note' => false,
            'type' => 5,
            'level' => '2',
            'school_id' => 2,
            'user_created' => 2,
            'token' => Crypt::encrypt('compras'),
            'catalog_id' => 17
        ]);

        \DB::table('catalogs')->insert([
            'id'=>19,
            'code' => '06-00-00-00-000',
            'name' => 'GASTOS',
            'style' => 'Grupo',
            'note' => false,
            'type' => 6,
            'level' => '1',
            'school_id' => 2,
            'user_created' => 2,
            'token' => Crypt::encrypt('gastos'),
            'catalog_id' => NULL
        ]);
        \DB::table('catalogs')->insert([
            'id'=>20,
            'code' => '06-01-00-00-000',
            'name' => 'GASTOS ADMINISTRATIVOS',
            'style' => 'Grupo',
            'note' => false,
            'type' => 6,
            'level' => '2',
            'school_id' => 2,
            'user_created' => 2,
            'token' => Crypt::encrypt('gastosA'),
            'catalog_id' => 19
        ]);
        \DB::table('catalogs')->insert([
            'id'=>21,
            'code' => '06-02-00-00-000',
            'name' => 'GASTOS OPERATIVOS',
            'style' => 'Grupo',
            'note' => false,
            'type' => 6,
            'level' => '2',
            'school_id' => 2,
            'user_created' => 2,
            'token' => Crypt::encrypt('gastos'),
            'catalog_id' => 19
        ]);
        \DB::table('catalogs')->insert([
            'id'=>22,
            'code' => '06-03-00-00-000',
            'name' => 'GASTOS FINANCIEROS',
            'style' => 'Grupo',
            'note' => false,
            'type' => 6,
            'level' => '2',
            'school_id' => 2,
            'user_created' => 2,
            'token' => Crypt::encrypt('gastos'),
            'catalog_id' => 19
        ]);
        \DB::table('catalogs')->insert([
            'id'=>23,
            'code' => '06-04-00-00-000',
            'name' => 'OTROS GASTOS',
            'style' => 'Grupo',
            'note' => false,
            'type' => 6,
            'level' => '2',
            'school_id' => 2,
            'user_created' => 2,
            'token' => Crypt::encrypt('gastos'),
            'catalog_id' => 19
        ]);
        \DB::table('catalogs')->insert([
            'id'=>24,
            'code' => '06-04-01-00-000',
            'name' => 'GASTOS NO DESUCIBLES',
            'style' => 'Detalle',
            'note' => false,
            'type' => 6,
            'level' => '2',
            'school_id' => 2,
            'user_created' => 2,
            'token' => Crypt::encrypt('gastos'),
            'catalog_id' => 23
        ]);
    }
}
