<?php
/**
 * Created by PhpStorm.
 * User: Amwar
 * Date: 24/02/2017
 * Time: 03:31 PM
 */
Route::get('taller-de-celulares/ver', ['as' => 'ver-taller-de-celulares', 'uses' => 'Workshops\CellphoneController@index']);
Route::get('taller-de-celulares/crear', ['as' => 'crear-taller-de-celulares', 'uses' => 'Workshops\CellphoneController@create']);
Route::get('taller-de-celulares/tecnico/{token}', ['as' => 'tecnico-taller-de-celulares', 'uses' => 'Workshops\CellphoneController@technical']);
Route::get('taller-de-celulares/pdf/{token}', ['as' => 'ficha-taller-de-celulares', 'uses' => 'Workshops\CellphoneController@pdfFile']);
Route::post('taller-de-celulares/save', 'Workshops\CellphoneController@store');
Route::post('taller-de-celulares/technical', 'Workshops\CellphoneController@postTechnical');
Route::get('taller-de-celulares/editar/{id}', ['as' => 'editar-taller-de-celulares', 'uses' => 'Workshops\CellphoneController@edit']);
Route::delete('taller-de-celulares/delete/{id}', ['as' => 'delete-taller-de-celulares', 'uses' => 'Workshops\CellphoneController@destroy']);
Route::patch('taller-de-celulares/active/{id}', ['as' => 'active-taller-de-celulares', 'uses' => 'Workshops\CellphoneController@active']);
Route::put('taller-de-celulares/update/{id}', 'Workshops\CellphoneController@update');

Route::post('taller-de-celulares/models', 'Workshops\CellphoneController@getModels');