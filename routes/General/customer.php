<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 18/01/16
 * Time: 11:59 AM
 */

Route::get('clientes/ver', ['as' => 'ver-clientes', 'uses' => 'Generales\CustomerController@index']);
Route::get('clientes/crear', ['as' => 'crear-clientes', 'uses' => 'Generales\CustomerController@create']);
Route::post('clientes/save', 'Generales\CustomerController@store');

Route::get('clientes/editar/{token}', ['as' => 'editar-clientes', 'uses' => 'Generales\CustomerController@edit']);
Route::post('clientes/update', 'Generales\CustomerController@update');