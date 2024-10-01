<?php
/**
 * Created by PhpStorm.
 * User: Anwar Sarmiento
 * Date: 06/12/2016
 * Time: 10:35 AM
 */
Route::get('modelo-de-vehiculo/ver', ['as' => 'ver-modelo-de-vehiculo', 'uses' => 'Workshops\ModelOfTheVehicleController@index']);
Route::get('modelo-de-vehiculo/crear', ['as' => 'crear-modelo-de-vehiculo', 'uses' => 'Workshops\ModelOfTheVehicleController@create']);
Route::post('modelo-de-vehiculo/save', 'Workshops\ModelOfTheVehicleController@store');
Route::get('modelo-de-vehiculo/editar/{id}', ['as' => 'editar-modelo-de-vehiculo', 'uses' => 'Workshops\ModelOfTheVehicleController@edit']);
Route::delete('modelo-de-vehiculo/delete/{id}', ['as' => 'delete-modelo-de-vehiculo', 'uses' => 'Workshops\ModelOfTheVehicleController@destroy']);
Route::patch('modelo-de-vehiculo/active/{id}', ['as' => 'active-modelo-de-vehiculo', 'uses' => 'Workshops\ModelOfTheVehicleController@active']);
Route::put('modelo-de-vehiculo/update/{id}', 'Workshops\ModelOfTheVehicleController@update');


Route::get('modelo-de-telefono/ver', ['as' => 'ver-modelo-de-telefono', 'uses' => 'Workshops\ModelOfTheVehicleController@index']);
Route::get('modelo-de-telefono/crear', ['as' => 'crear-modelo-de-telefono', 'uses' => 'Workshops\ModelOfTheVehicleController@create']);
Route::post('modelo-de-telefono/save', 'Workshops\ModelOfTheVehicleController@store');
Route::get('modelo-de-telefono/editar/{id}', ['as' => 'editar-modelo-de-telefono', 'uses' => 'Workshops\ModelOfTheVehicleController@edit']);
Route::delete('modelo-de-telefono/delete/{id}', ['as' => 'delete-modelo-de-telefono', 'uses' => 'Workshops\ModelOfTheVehicleController@destroy']);
Route::patch('modelo-de-telefono/active/{id}', ['as' => 'active-modelo-de-telefono', 'uses' => 'Workshops\ModelOfTheVehicleController@active']);
Route::put('modelo-de-telefono/update/{id}', 'Workshops\ModelOfTheVehicleController@update');