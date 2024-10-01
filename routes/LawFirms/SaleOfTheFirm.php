<?php
/**
 * Created by PhpStorm.
 * User: Amwar
 * Date: 11/12/2016
 * Time: 07:57 PM
 */
Route::get('factura-bufete/ver', ['as' => 'ver-factura-bufete', 'uses' => 'LawFirms\SaleOfTheFirmsController@index']);
Route::get('factura-bufete/crear', ['as' => 'crear-factura-bufete', 'uses' => 'LawFirms\SaleOfTheFirmsController@create']);
Route::get('factura-bufete/pdf/{token}', ['as' => 'pdf-factura-bufete', 'uses' => 'LawFirms\SaleOfTheFirmsController@pdf']);
Route::post('factura-bufete/save', ['as' => 'save-factura-bufete', 'uses' => 'LawFirms\SaleOfTheFirmsController@storeLine']);
Route::post('factura-bufete/save/all', ['as' => 'store-factura-bufete', 'uses' => 'LawFirms\SaleOfTheFirmsController@store']);
Route::delete('factura-bufete/delete/{token}', ['as' => 'eliminar-factura-bufete',
    'uses' => 'LawFirms\SaleOfTheFirmsController@deleteLine']);