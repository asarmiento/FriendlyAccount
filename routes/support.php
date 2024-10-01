<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 12/07/16
 * Time: 07:39 PM
 */

Route::get('support/ticket', ['as'=>'support-report', 'uses'=>'Generales\SupportController@create']);
Route::post('support/save', ['as'=>'support-save', 'uses'=>'Generales\SupportController@store']);