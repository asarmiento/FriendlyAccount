<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 27/12/15
 * Time: 07:33 PM
 */

Route::get('materias-primas/ver', ['as' => 'ver-rawMaterials', 'uses' => 'Generales\RawMaterialController@index']);
Route::get('materias-primas/crear', ['as' => 'crear-rawMaterials', 'uses' => 'Generales\RawMaterialController@create']);
Route::post('materias-primas/save', 'Generales\RawMaterialController@store');

Route::get('materias-primas/editar/{token}', ['as' => 'editar-rawMaterials', 'uses' => 'Generales\RawMaterialController@edit']);
Route::post('materias-primas/update', 'Generales\RawMaterialController@update');
Route::delete('materias-primas/deleteDetail/{id}', 'Generales\RawMaterialController@deleteDetail');

Route::get('proveedores/materias-primas/{token}', ['as' => 'supplier-rawMaterials', 'uses' => 'Generales\RawMaterialController@productDetail']);