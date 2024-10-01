<?php

Route::get('auxiliares-proveedores/ver', ['as' => 'ver-proveedores-auxiliares', 'uses' => 'AuxiliarySupplierController@index']);
Route::get('auxiliares-proveedores/crear', ['as' => 'crear-auxiliares-proveedor', 'uses' => 'AuxiliarySupplierController@create']);
Route::post('auxiliares-proveedores/save', 'AuxiliarySupplierController@store');
Route::get('auxiliares-proveedores/ver/{token}', ['as' => 'proveedores-auxiliares', 'uses' => 'AuxiliarySupplierController@view']);
Route::delete('auxiliares-proveedores/delete/{token}', ['as' => 'delete-asientos-auxiliares', 'uses' => 'AuxiliarySupplierController@destroy']);
Route::patch('auxiliares-proveedores/active/{token}', ['as' => 'active-asientos-auxiliares', 'uses' => 'AuxiliarySupplierController@active']);
Route::put('auxiliares-proveedores/update', 'AuxiliarySupplierController@update');
Route::post('auxiliares-proveedores/status', 'AuxiliarySupplierController@status');
Route::delete('auxiliares-proveedores/deleteDetail/{id}', 'AuxiliarySupplierController@deleteDetail');
Route::post('auxiliares-proveedores/other', 'AuxiliarySupplierController@other');