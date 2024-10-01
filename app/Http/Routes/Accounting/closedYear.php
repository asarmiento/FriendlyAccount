<?php

Route::get('cierre-fiscal/ver', ['as' => 'ver-cierre-fiscal', 'uses' => 'Accounting\ClosedYearController@index']);
Route::post('cierre-fiscal/ver', ['as' => 'validar-cierre-1', 'uses' => 'Accounting\ClosedYearController@validatePass']);
Route::post('cierre-fiscal/validateSeat', ['as' => 'validar-cierre-2', 'uses' => 'Accounting\ClosedYearController@verificationSeatPending']);
Route::post('cierre-fiscal/validateBalance', ['as' => 'validar-cierre-3', 'uses' => 'Accounting\ClosedYearController@verificationBalanceAuxConta']);
Route::post('cierre-fiscal/finally', ['as' => 'cierra-finally', 'uses' => 'Accounting\ClosedYearController@generationSeatFinish']);

Route::get('cierre-fiscal/crear', ['as' => 'crear-cierre-fiscal', 'uses' => 'Accounting\ClosedYearController@create']);






