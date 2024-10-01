<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 12/04/16
 * Time: 11:49 PM
 */
Route::get('denominacion-monedas/ver', ['as' => 'ver-monedas', 'uses' => 'Generales\CurrenciesController@index']);
Route::get('denominacion-monedas/crear', ['as' => 'crear-monedas', 'uses' => 'Generales\CurrenciesController@create']);
Route::get('denominacion-monedas/editar/{id}', ['as' => 'editar-currencies', 'uses' => 'Generales\CurrenciesController@edit']);
Route::post('denominacion-monedas/save', 'Generales\CurrenciesController@store');
Route::post('denominacion-monedas/update', 'Generales\CurrenciesController@update');