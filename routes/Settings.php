<?php

Route::get('configuracion/ver', ['as' => 'ver-configuracion', 'uses' => 'SettingsController@index']);
Route::get('conciliacion-cuentas-control/ver', ['as' => 'ver-conciliacion', 'uses' => 'SettingsController@conciliacion']);
Route::post('conciliacion-cuentas-control/lista', ['as' => 'conciliacion-post', 'uses' => 'SettingsController@postConciliacion']);
Route::get('configuracion/crear', ['as' => 'crear-configuracion', 'uses' => 'SettingsController@create']);
Route::get('cambio-clave/ver', ['as' => 'change-password', 'uses' => 'UsersController@password']);


Route::post('cambio-clave/updatePassword', ['as' => 'changePassword', 'uses' => 'UsersController@updatePassword']);
Route::post('configuracion/save', 'SettingsController@store');
Route::get('configuracion/editar/{id}', ['as' => 'editar-configuracion', 'uses' => 'SettingsController@edit']);
Route::delete('configuracion/delete/{token}', ['as' => 'delete-configuracion', 'uses' => 'SettingsController@destroy']);
Route::patch('configuracion/active/{token}', ['as' => 'active-configuracion', 'uses' => 'SettingsController@active']);
Route::post('configuracion/update', 'SettingsController@update');
Route::post('configuracion/status', 'SettingsController@status');
Route::delete('configuracion/deleteDetail/{id}', 'SettingsController@deleteDetail');