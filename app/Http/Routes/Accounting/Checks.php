<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 30/09/15
 * Time: 09:52 AM
 */

Route::get('cheques/ver', ['as' => 'ver-cheques', 'uses' => 'Accounting\CheckController@index']);
Route::get('cheques/crear', ['as' => 'crear-cheques', 'uses' => 'Accounting\CheckController@create']);
Route::post('cheques/save', 'Accounting\CheckController@store');
Route::post('cheques/anular', 'Accounting\CheckController@store');

Route::get('cheques/ver/{token}', ['as' => 'cheques', 'uses' => 'Accounting\CheckController@view']);
Route::post('cheques/status', 'Accounting\CheckController@status');
Route::delete('cheques/deleteDetail/{id}', 'Accounting\CheckController@deleteDetail');
