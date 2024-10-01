<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 03/01/16
 * Time: 07:35 PM
 */

Route::get('menu-restaurante/ver', ['as' => 'ver-menuRestaurant', 'uses' => 'Restaurant\MenuRestaurantController@index']);
Route::get('menu-restaurante/crear', ['as' => 'crear-menuRestaurant', 'uses' => 'Restaurant\MenuRestaurantController@create']);
Route::post('menu-restaurante/save', 'Restaurant\MenuRestaurantController@store');
Route::get('menu-restaurante/editar/{token}', ['as' => 'edit-menu', 'uses' => 'Restaurant\MenuRestaurantController@edit']);
Route::post('menu-restaurante/update', 'Restaurant\MenuRestaurantController@update');

Route::post('menu-restaurante/eliminar', ['as' => 'destroy-menu', 'uses' => 'Restaurant\MenuRestaurantController@destroy']);
Route::get('menu-restaurante/report', 'Restaurant\MenuRestaurantController@salePdf');
Route::get('menu-restaurante/reportes', ['as' => 'report-cooken', 'uses' => 'Restaurant\MenuRestaurantController@recordSale']);

Route::post('menu-restaurante/report', 'Restaurant\MenuRestaurantController@reportPdf');
Route::get('menu-restaurante/componentes/{token}', ['as' => 'componente-menuRestaurant', 'uses' => 'Restaurant\MenuRestaurantController@component']);

Route::post('menu-restaurante/componentes/deleteComponent', 'Restaurant\MenuRestaurantController@deleteComponent');
Route::post('componentes/menu-restaurante/save', 'Restaurant\MenuRestaurantController@postComponent');
