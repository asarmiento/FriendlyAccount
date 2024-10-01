<?php
Route::get('code/generate',['as' => 'code.index', 'uses' => 'Auth\CodeController@index']);
Route::post('code/generate',['as' => 'code.store', 'uses' => 'Auth\CodeController@store']);
