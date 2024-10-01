<?php
/**
 * Created by PhpStorm.
 * User: anwarsarmiento
 * Email: asarmiento@sistemasamigables.com
 * Date: 31/1/17
 * Time: 18:42
 */
Route::get('tipo-de-empresa/ver', ['as' => 'ver-empresas', 'uses' => 'Generales\TypeOfCompanyController@index']);
Route::get('tipo-de-empresa/crear', ['as' => 'crear-empresas', 'uses' => 'Generales\TypeOfCompanyController@create']);
Route::get('tipo-de-empresa/editar/{id}', ['as' => 'editar-empresas', 'uses' => 'Generales\TypeOfCompanyController@edit']);
Route::post('tipo-de-empresa/save', 'Generales\TypeOfCompanyController@store');
Route::post('tipo-de-empresa/update', 'Generales\TypeOfCompanyController@update');