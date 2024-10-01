<?php

Route::get('asientos/ver', ['as' => 'ver-asientos', 'uses' => 'SeatingController@index']);
Route::get('asientos/ver-anteriores', ['as' => 'ver-asientos-anteriores', 'uses' => 'SeatingController@indexNo']);
Route::get('asientos/crear', ['as' => 'crear-asientos', 'uses' => 'SeatingController@create']);
Route::get('asientos/reimprimir', ['as' => 'reimprimir-asientos', 'uses' => 'SeatingController@reimprimir']);
Route::post('asientos/save', 'SeatingController@store');
Route::get('asientos/ver/{token}', ['as' => 'asientos', 'uses' => 'SeatingController@view']);
Route::delete('asientos/delete/{token}', ['as' => 'delete-asientos', 'uses' => 'SeatingController@destroy']);
Route::patch('asientos/active/{token}', ['as' => 'active-asientos', 'uses' => 'SeatingController@active']);
Route::put('asientos/update', 'SeatingController@update');
Route::post('asientos/status', 'SeatingController@status');
Route::delete('asientos/deleteDetail/{id}', 'SeatingController@deleteDetail');
