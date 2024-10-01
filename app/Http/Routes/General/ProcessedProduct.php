<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 31/12/15
 * Time: 01:06 PM
 */

Route::get('productos-elaborados/ver', ['as' => 'ver-cooken', 'uses' => 'Generales\ProcessedProductController@index']);
Route::get('productos-elaborados/crear', ['as' => 'crear-cooked', 'uses' => 'Generales\ProcessedProductController@create']);
Route::get('productos-elaborados/editar/{token}', ['as' => 'edit-cooked', 'uses' => 'Generales\ProcessedProductController@edit']);
Route::get('productos-elaborados/report', 'Generales\ProcessedProductController@salePdf');
Route::get('productos-elaborados/reportes', ['as' => 'report-cooken', 'uses' => 'Generales\ProcessedProductController@recordSale']);

Route::post('productos-elaborados/report', 'Generales\ProcessedProductController@reportPdf');
Route::post('productos-elaborados/save', 'Generales\ProcessedProductController@store');
Route::post('productos-elaborados/update', 'Generales\ProcessedProductController@update');


Route::get('recetas/{token}', ['as' => 'crear-recipes', 'uses' => 'Generales\ProcessedProductController@recipes']);
Route::post('recetas-units', ['as' => 'units-recipes', 'uses' => 'Generales\ProcessedProductController@changeUnits']);
Route::post('recetas/save', 'Generales\ProcessedProductController@storeRecipes');
Route::post('delete/recetas',['as' => 'delete-recipes', 'uses' =>  'Generales\ProcessedProductController@deleteRecipes']);