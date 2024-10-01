<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 13/07/16
 * Time: 09:28 PM
 */

Route::get('empleados/ver', ['as' => 'ver-employess', 'uses' => 'Generales\EmployessController@index']);
Route::get('empleados/crear', ['as' => 'crear-employess', 'uses' => 'Generales\EmployessController@create']);
Route::get('empleados/registro-de-horas', ['as' => 'horas-employess', 'uses' => 'Generales\EmployessController@times']);


Route::post('empleado/save', 'Generales\EmployessController@store');
Route::post('empleado/registro-de-horas/save', 'Generales\EmployessController@timesStore');

Route::get('empleado/editar/{token}', ['as' => 'editar-employess', 'uses' => 'Generales\EmployessController@edit']);
Route::delete('empleado/delete/{token}', ['as' => 'delete-employess', 'uses' => 'Generales\EmployessController@destroy']);
Route::patch('empleado/active/{token}', ['as' => 'active-employess', 'uses' => 'Generales\EmployessController@active']);
Route::put('empleado/update/{token}', 'Generales\EmployessController@update');