<?php
/**
 * Created by PhpStorm.
 * User: Amwar
 * Date: 22/11/2016
 * Time: 11:24 AM
 */


Route::get('comisionista/ver', ['as' => 'ver-commission', 'uses' => 'Restaurant\CommissionAgentsController@index']);
Route::get('comisionista/crear', ['as' => 'crear-commission', 'uses' => 'Restaurant\CommissionAgentsController@create']);
Route::get('comisionista/editar/{token}', ['as' => 'editar-commission', 'uses' => 'Restaurant\CommissionAgentsController@edit']);
Route::post('comisionista/editar/{token}', ['as' => 'editar-commission', 'uses' => 'Restaurant\CommissionAgentsController@update']);
Route::post('comisionista/save', ['as' => 'save-commission', 'uses' => 'Restaurant\CommissionAgentsController@store']);