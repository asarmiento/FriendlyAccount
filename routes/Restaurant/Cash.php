<?php

Route::post('cash', ['as' => 'cash-invoice', 'uses' => 'Restaurant\CashController@cashInvoice']);
Route::post('cashSplit', ['as' => 'cashSplit-invoice', 'uses' => 'Restaurant\CashController@cashInvoiceSplit']);
Route::get('caja/cierre', ['as' => 'cierre-de-caja', 'uses' => 'Restaurant\CashController@closed']);
Route::post('caja/search', ['as' => 'cotejar-caja', 'uses' => 'Restaurant\CashController@search']);
Route::post('caja/court', ['as' => 'cotejar-caja', 'uses' => 'Restaurant\CashController@court']);
Route::get('caja/pago-proveedores', ['as' => 'payment-caja', 'uses' => 'Restaurant\CashController@paymentSuppliers']);
Route::post('caja/pago-proveedores', ['as' => 'payment-save', 'uses' => 'Restaurant\CashController@savePaymentSuppliers']);

Route::post('caja/user', ['as' => 'cotejar-usuario', 'uses' => 'Restaurant\CashController@validateUser']);
Route::get('/cash/express', ['as' => 'cash.express', 'uses' => 'Restaurant\CashController@cashExpress']);
Route::post('/cash/listMenu', ['as' => 'cash.list.menu', 'uses' => 'Restaurant\CashController@listMenu']);
Route::get('/cash/listOrders', ['as' => 'cash.list.orders', 'uses' => 'Restaurant\CashController@listOrders']);
Route::put('/cash/updateOrder/{id}', ['as' => 'cash.update.order', 'uses' => 'Restaurant\CashController@updateOrder']);