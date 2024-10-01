<?php
/**
 * Created by PhpStorm.
 * User: Anwar Sarmiento
 * Date: 22/06/2015
 * Time: 10:43 PM
 */

use AccountHon\Entities\TypeSeat;

Route::get('tipos-de-asientos/ver', ['as' => 'ver-tipos-de-asientos', 'uses' => 'TypeSeatController@index']);
Route::get('number-checks', function (){
    return TypeSeat::where('school_id', userSchool()->id)->where('abbreviation', 'CK')->first();
});

Route::get('tipos-de-asientos/crear', ['as' => 'crear-tipos-de-asientos', 'uses' => 'TypeSeatController@create']);
Route::post('tipos-de-asientos/save', 'TypeSeatController@store');
Route::post('store-number-checks', 'TypeSeatController@updateCheck');
Route::get('tipos-de-asientos/editar/{token}', ['as' => 'editar-tipos-de-asientos', 'uses' => 'TypeSeatController@edit']);
Route::put('tipos-de-asientos/update', 'TypeSeatController@update');