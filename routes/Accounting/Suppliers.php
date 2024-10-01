<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 26/10/15
 * Time: 07:11 PM
 */


Route::get('proveedores/ver', ['as' => 'ver-proveedores', 'uses' => 'SupplierController@index']);
Route::get('proveedores/crear', ['as' => 'crear-proveedores', 'uses' => 'SupplierController@create']);
Route::post('proveedores/save', 'SupplierController@store');
Route::post('proveedores/update', 'SupplierController@update');
Route::get('proveedores/editar/{token}', ['as' => 'editar-proveedor', 'uses' => 'SupplierController@edit']);
Route::get('proveedores/marcas/{token}',['as' => 'ver-proveedores-marcas', 'uses' => 'SupplierController@getBrands']);
Route::post('proveedores/marcas/save', 'SupplierController@storeBrands');