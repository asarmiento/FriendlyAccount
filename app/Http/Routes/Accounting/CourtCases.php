<?php

Route::get('cortes-de-caja/ver', ['as' => 'ver-cortes-de-caja', 'uses' => 'CourtCaseController@index']);
Route::get('cortes-de-caja/crear', ['as' => 'deposit-cortes-de-caja', 'uses' => 'CourtCaseController@deposit']);
Route::get('cortes-de-caja/detalle', ['as' => 'crear-cortes-de-caja', 'uses' => 'CourtCaseController@create']);
Route::post('cortes-de-caja/save', 'CourtCaseController@store');
Route::get('cortes-de-caja/impresion/{token}', ['as' => 'impresion-cortes-de-caja', 'uses' => 'CourtCaseController@report']);
Route::get('cortes-de-caja/editar/{token}', ['as' => 'editar-cortes-de-caja', 'uses' => 'CourtCaseController@edit']);
Route::post('cortes-de-caja/deposit/save', 'CourtCaseController@saveDeposit');
Route::post('cortes-de-caja/delete', 'CourtCaseController@deleteDeposit');