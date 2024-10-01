<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 28/12/15
 * Time: 09:32 AM
 */

Route::get('mesas/ver', ['as' => 'ver-mesas', 'uses' => 'Restaurant\TableSalonController@index']);
Route::get('mesas/crear', ['as' => 'crear-mesas', 'uses' => 'Restaurant\TableSalonController@create']);
Route::get('mesas/cuenta', ['as' => 'crear-mesas-Restauran', 'uses' => 'Restaurant\TableSalonController@createRestaurant']);
Route::post('mesas/save', 'Restaurant\TableSalonController@store');
Route::get('mesas/editar/{token}', ['as' => 'editar-mesa', 'uses' => 'Restaurant\TableSalonController@edit']);
Route::post('mesas/update', 'Restaurant\TableSalonController@update');