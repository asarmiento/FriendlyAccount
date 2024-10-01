<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 08/11/15
 * Time: 11:43 AM
 */

Route::get('reportes/estado-de-resultado', 'FpdfController@viewEstadoResultado');
Route::post('reportes/estado-de-resultado', ['as'=>'reports-result','uses'=>'FpdfController@pdfEstadoResultado']);
Route::get('reportes/estado-de-situacion', 'FpdfController@pdfEstadoSituacion');
Route::get('reportes/ticket-orden', 'ReportePdf\ReportTicketController@ticket');
Route::get('reportes/estado-de-cuenta-de-alumno', 'ReportExcel\EstadoCuentaAuxiliaryController@estadoEstuden');
Route::post('reportes/estado-de-cuenta-de-alumno', ['as'=>'reports-student','uses'=>'ReportExcel\EstadoCuentaAuxiliaryController@estadoCuentaAuxiliary']);
Route::get('reportes/pdf/buy/{token}', ['as'=>'report-pdf-buy','uses'=>'ReportPdf\ReportBuyController@pdfBuy']);