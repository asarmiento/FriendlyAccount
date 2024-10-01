<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 03/02/16
 * Time: 09:27 PM
 */

Route::get('grupo-de-menu/ver', ['as' => 'ver-grupo-de-menu', 'uses' => 'Restaurant\GroupMenuController@index']);
Route::get('grupo-de-menu/crear', ['as' => 'crear-grupo-de-menu', 'uses' => 'Restaurant\GroupMenuController@create']);
Route::post('grupo-de-menu/save', 'Restaurant\GroupMenuController@store');

Route::get('grupo-de-menu/editar/{token}', ['as' => 'editar-grupo-de-menu', 'uses' => 'Restaurant\GroupMenuController@edit']);
Route::post('grupo-de-menu/update', 'Restaurant\GroupMenuController@update');