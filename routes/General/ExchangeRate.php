<?php
/**
 * Created by PhpStorm.
 * User: Amwar
 * Date: 24/01/2017
 * Time: 10:41 AM
 */


Route::get('tipos-de-cambio/ver', ['as' => 'ver-tipoCambio', 'uses' => 'Generales\ExchangeRateController@index']);
Route::get('tipos-de-cambio/crear', ['as' => 'crear-tipoCambio', 'uses' => 'Generales\ExchangeRateController@create']);
Route::get('eliminar/tipos-de-cambio/{token}',['as'=>'eliminar-tc', 'uses'=> 'Generales\ExchangeRateController@destroy']);
Route::post('tipos-de-cambio/save', 'Generales\ExchangeRateController@store');