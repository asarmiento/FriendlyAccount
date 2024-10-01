<?php
/**
 * Created by PhpStorm.
 * User: anwarsarmiento
 * Email: asarmiento@sistemasamigables.com
 * Date: 31/1/17
 * Time: 21:10
 */
Route::get('cuentas-maestras/ver', ['as' => 'ver-cuentas', 'uses' => 'Accounting\ListOfAccountController@index']);
Route::get('cuentas-maestras/crear', ['as' => 'crear-cuentas', 'uses' => 'Accounting\ListOfAccountController@create']);
Route::get('cuentas-maestras/editar/{token}', ['as' => 'editar-cuentas', 'uses' => 'Accounting\ListOfAccountController@edit']);
Route::post('cuentas-maestras/save', 'Accounting\ListOfAccountController@store');
Route::post('cuentas-maestras/update', 'Accounting\ListOfAccountController@update');