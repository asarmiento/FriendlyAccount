<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 30/12/15
 * Time: 02:24 PM
 */

Route::get('pedidos-de-cocina/ver', ['as' => 'ver-kitchen', 'uses' => 'Restaurant\KitchenOrdersController@index']);
Route::get('pedidos-de-cocina/crear', ['as' => 'crear-kitchen', 'uses' => 'Restaurant\KitchenOrdersController@create']);
Route::post('pedidos-de-cocina/save', 'Restaurant\KitchenOrdersController@store');

// Orders of kitchen
Route::get('cocina',['as' => 'ver-ordenes', 'uses' => 'Restaurant\KitchenOrdersController@orders']);
Route::post('cocina',['as' => 'grabar-orden', 'uses' => 'Restaurant\KitchenOrdersController@cookedOrder']);