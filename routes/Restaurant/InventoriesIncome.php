<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 27/12/15
 * Time: 05:58 PM
 */

Route::get('ingresos-inventario/ver', ['as' => 'ver-ingresos-inventario', 'uses' => 'Restaurant\InventoriesIncomeController@index']);
Route::get('ingresos-inventario/crear', ['as' => 'crear-ingresos-inventario', 'uses' => 'Restaurant\InventoriesIncomeController@create']);
Route::post('ingresos-inventario/save', 'Restaurant\InventoriesIncomeController@store');

Route::get('ingresos-inventario/ver/{token}', ['as' => 'cheques', 'uses' => 'Restaurant\InventoriesIncomeController@view']);
Route::post('ingresos-inventario/status', 'Restaurant\InventoriesIncomeController@status');
Route::delete('ingresos-inventario/deleteDetail/{id}', 'Restaurant\InventoriesIncomeController@deleteDetail');
Route::post('ingresos-inventario/supplier',['as' => 'filter-supplier', 'uses' => 'Restaurant\InventoriesIncomeController@filterSupplier']);