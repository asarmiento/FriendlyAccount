<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use Illuminate\Database\Seeder;
/**
 * Description of MenuTableSeeder
 *
 * @author Anwar Sarmiento
 */
class MenuTableSeeder extends Seeder {
    //put your code here
    
    public function run() {
         \DB::table('menus')->insert([
            'id' => 1,
            'name' => 'MENU',
            'url' => '/MENU',
             'priority' => 1,
             'icon_font' => 'fa fa-bars'
         ]);
         \DB::table('menus')->insert([
            'id' => 2,
            'name' => 'USUARIOS',
            'url' => '/USUARIOS',
             'priority' => 1,
             'icon_font' => 'fa fa-user'
         ]);
        \DB::table('menus')->insert([
            'id' => 5,
            'name' => 'COMPRAS',
            'url' => '/institucion/inst/compras',
            'priority' => 1,
            'icon_font' => 'fa fa-shoping-cart'
        ]);
           \DB::table('menus')->insert([
            'id' => 3,
            'name' => 'ROLES',
            'url' => '/ROLES',
            'priority' => 1,
            'icon_font' => 'fa fa-list-alt'
         ]);
           \DB::table('menus')->insert([
            'id' => 4,
            'name' => 'TIPOS DE USUARIOS',
            'url' => '/TIPOS-DE-USUARIOS',
               'priority' => 1,
               'icon_font' => 'fa fa-users'
         ]);
        \DB::table('menus')->insert([
            'id' => 6,
            'name' => 'TIPOS DE Asientos',
            'url' => '/institucion/inst/tipos-de-asientos',
            'priority' => 1,
            'icon_font' => 'fa fa-bookmark'
        ]);
        \DB::table('menus')->insert([
            'id' => 7,
            'name' => 'Periodo Contables',
            'url' => '/institucion/inst/periodos-contables',
            'priority' => 1,
            'icon_font' => 'fa fa-calendar'
        ]);
        \DB::table('menus')->insert([
            'id' => 8,
            'name' => 'Catálogos',
            'url' => '/institucion/inst/catalogos',
            'priority' => 1,
            'icon_font' => 'fa fa-book'
        ]);
        \DB::table('menus')->insert([
            'id' => 9,
            'name' => 'Cheques',
            'url' => '/institucion/inst/cheques',
            'priority' => 1,
            'icon_font' => 'fa fa-money'
        ]);
        \DB::table('menus')->insert([
            'id' => 10,
            'name' => 'Grados',
            'url' => '/institucion/inst/grados',
            'priority' => 1,
            'icon_font' => 'fa fa-graduation-cap'
        ]);
        \DB::table('menus')->insert([
            'id' => 11,
            'name' => 'Asientos Auxiliares',
            'url' => '/institucion/inst/asientos-auxiliares',
            'priority' => 1,
            'icon_font' => 'glyphicon glyphicon-book'
        ]);
        \DB::table('menus')->insert([
            'id' => 12,
            'name' => 'Reportes',
            'url' => '/institucion/inst/reportes',
            'priority' => 1,
            'icon_font' => 'fa fa-bar-chart'
        ]);
        \DB::table('menus')->insert([
            'id' => 13,
            'name' => 'costos',
            'url' => '/institucion/inst/costos',
            'priority' => 1,
            'icon_font' => 'fa fa-usd'
        ]);
        \DB::table('menus')->insert([
            'id' => 14,
            'name' => 'recibos',
            'url' => '/institucion/inst/recibos',
            'priority' => 1,
            'icon_font' => 'glyphicon glyphicon-duplicate'
        ]);
       \DB::table('menus')->insert([
            'id' => 15,
            'name' => 'recibos auxiliares',
            'url' => '/institucion/inst/recibos-auxiliares',
            'priority' => 1,
            'icon_font' => 'glyphicon glyphicon-duplicate'
        ]);
       \DB::table('menus')->insert([
            'id' => 16,
            'name' => 'asientos',
            'url' => '/institucion/inst/asientos',
            'priority' => 1,
            'icon_font' => 'glyphicon glyphicon-book'
        ]);
       \DB::table('menus')->insert([
            'id' => 17,
            'name' => 'estudiantes',
            'url' => '/institucion/inst/estudiantes',
            'priority' => 1,
            'icon_font' => 'glyphicon glyphicon-education'
        ]);
       \DB::table('menus')->insert([
            'id' => 18,
            'name' => 'cortes de caja',
            'url' => '/institucion/inst/cortes-de-caja',
            'priority' => 1,
            'icon_font' => 'fa fa-check-square-o'
        ]);
        \DB::table('menus')->insert([
            'id' => 19,
            'name' => 'proveedores',
            'url' => '/institucion/inst/proveedores',
            'priority' => 1,
            'icon_font' => 'fa fa-truck'
        ]);

        \DB::table('menus')->insert([
            'id' => 20,
            'name' => 'Ingresos Inventario',
            'url' => '/institucion/inst/ingresos-inventario',
            'priority' => 1,
            'icon_font' => 'fa fa-cart-plus'
        ]);
        \DB::table('menus')->insert([
            'id' => 21,
            'name' => 'productos-crudos',
            'url' => '/institucion/inst/productos-crudos',
            'priority' => 1,
            'icon_font' => 'fa fa-book'
        ]);
        \DB::table('menus')->insert([
            'id' => 22,
            'name' => 'marcas',
            'url' => '/institucion/inst/marcas',
            'priority' => 1,
            'icon_font' => 'fa fa-cubes'
        ]);

        \DB::table('menus')->insert([
            'id' => 23,
            'name' => 'pedidos de Cocina',
            'url' => '/institucion/inst/pedidos-de-cocina',
            'priority' => 1,
            'icon_font' => 'fa fa-cutlery'
        ]);

        \DB::table('menus')->insert([
            'id' => 24,
            'name' => 'Productos Cocidos',
            'url' => '/institucion/inst/productos-cocidos',
            'priority' => 1,
            'icon_font' => 'fa fa-coffee'
        ]);


        \DB::table('menus')->insert([
            'id' => 25,
            'name' => 'Menu Restaurante',
            'url' => '/institucion/inst/menu-restaurante',
            'priority' => 1,
            'icon_font' => 'fa fa-cutlery'
        ]);


        \DB::table('menus')->insert([
            'id' => 26,
            'name' => 'Mesas',
            'url' => '/institucion/inst/mesas',
            'priority' => 1,
            'icon_font' => 'fa fa-cutlery'
        ]);

        \DB::table('menus')->insert([
            'id' => 27,
            'name' => 'Grupo de Menu',
            'url' => '/institucion/inst/grupo-de-menu',
            'priority' => 1,
            'icon_font' => 'fa fa-bars'
        ]);

        \DB::table('menus')->insert([
            'id' => 28,
            'name' => 'Cierre Fiscal',
            'url' => '/institucion/inst/cierre-fiscal',
            'priority' => 1,
            'icon_font' => 'fa fa-paper-plane'
        ]);


        \DB::table('menus')->insert([
            'id' => 29,
            'name' => 'Denominacion Monedas',
            'url' => '/institucion/inst/denominacion-monedas',
            'priority' => 1,
            'icon_font' => 'fa fa-money'
        ]);

        \DB::table('menus')->insert([
            'id' => 30,
            'name' => 'Facturas',
            'url' => '/institucion/inst/facturas',
            'priority' => 1,
            'icon_font' => 'fa fa-usd'
        ]);

        \DB::table('menus')->insert([
            'id' => 31,
            'name' => 'cambio contraseña',
            'url' => '/institucion/inst/cambio-clave',
            'priority' => 1,
            'icon_font' => 'fa fa-cog'
        ]);
    }
}
