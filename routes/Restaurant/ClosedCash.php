<?php
/**
 * Created by PhpStorm.
 * User: anwarsarmiento
 * Email: asarmiento@sistemasamigables.com
 * Date: 27/12/16
 * Time: 21:22
 */


Route::get('cierres-de-cajas/ver', ['as' => 'ver-cierre-de-caja', 'uses' => 'Restaurant\CashController@closedCashIndex']);
Route::get('cierres-de-cajas/reporte', ['as' => 'reporte-cierre-de-caja', 'uses' => 'Restaurant\CashController@ReportClosedCash']);