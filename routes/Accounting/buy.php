<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 01/10/15
 * Time: 05:53 PM
 */


Route::get('compras/ver', ['as' => 'ver-compras', 'uses' => 'BuyController@index']);
Route::get('compras/crear', ['as' => 'crear-compra', 'uses' => 'BuyController@create']);
Route::post('compras/save', 'BuyController@store');

Route::get('compras/ver/{token}', ['as' => 'cheques', 'uses' => 'BuyController@view']);
Route::post('compras/status', 'BuyController@status');
Route::delete('compras/deleteDetail/{id}', 'BuyController@deleteDetail');
