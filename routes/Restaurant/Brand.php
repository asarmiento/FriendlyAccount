<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 28/12/15
 * Time: 09:32 AM
 */

Route::get('marcas/ver', ['as' => 'ver-marcas', 'uses' => 'Generales\BrandController@index']);
Route::get('marcas/crear', ['as' => 'crear-marcas', 'uses' => 'Generales\BrandController@create']);
Route::get('marcas/ver-proveedores/{token}', ['as' => 'ver-marcas-proveedores', 'uses' => 'Generales\BrandController@suppliers']);
Route::post('marcas/save', 'Generales\BrandController@store');

Route::get('marcas/editar/{token}', ['as' => 'editar-marca', 'uses' => 'Generales\BrandController@edit']);
Route::post('marcas/update', 'Generales\BrandController@update');