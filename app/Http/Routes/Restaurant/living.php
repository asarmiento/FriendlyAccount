<?php
/**
 * Created by PhpStorm.
 * User: anwar
 * Date: 19/01/16
 * Time: 07:30 PM
 */

Route::get('salon', ['as' => 'ver-salon', 'uses' => 'Restaurant\LivingController@livingTables']);
Route::post('print-restaurant', ['as' => 'print-restaurant', 'uses' => 'Restaurant\LivingController@printRestaurant']);
Route::post('salon-order', ['as' => 'salon-order', 'uses' => 'Restaurant\LivingController@store']);

Route::get('salon/{token}', ['as' => 'salon-token', 'uses' => 'Restaurant\LivingController@validateToken']);
Route::delete('salon/order/{token}', ['as' => 'delete-order', 'uses' => 'Restaurant\LivingController@deleteOrder']);
Route::post('salon-order/canceled', ['as' => 'canceled-order', 'uses' => 'Restaurant\LivingController@canceled']);
Route::post('salon/{token}', ['as' => 'cash-order-pending', 'uses' => 'Restaurant\LivingController@cash']);

Route::get('salon/{token_table}/menu/{token}', ['as' => 'menu-token', 'uses' => 'Restaurant\LivingController@menuToken']);

Route::get('salon/orders/{token_table}',['as' => 'orders-table', 'uses'=>'Restaurant\LivingController@orders']);
Route::get('salon/orders_kitchen/{token_table}', ['as' => 'orders-table', 'uses'=>'Restaurant\LivingController@ordersKitchen']);
Route::get('salon/orders_drinks/{token_table}', ['as' => 'orders-table', 'uses'=>'Restaurant\LivingController@ordersDrinks']);


Route::get('salon/print/{token_table}',['as' => 'print-table', 'uses'=>'Restaurant\LivingController@prints']);

Route::post('order-print',['as' => 'order-print', 'uses'=>'Restaurant\LivingController@printOrder']);
Route::post('salon-print',['as' => 'save-order', 'uses'=>'Restaurant\LivingController@saveOrder']);


Route::get('facturas-lista/ver',['as' => 'ver-facturas', 'uses' => 'Restaurant\LivingController@invoice']);

Route::get('salon/print-invoice/{token}', ['as' => 'reimpresion-facturas', 'uses' => 'Restaurant\LivingController@printInvoice']);

Route::get('salon/dividir-cuentas/{token}', ['as' => 'dividir-cuentas', 'uses' => 'Restaurant\LivingController@splitOrders']);
Route::get('salon/unir-cuentas/{token}', ['as' => 'unir-cuentas', 'uses' => 'Restaurant\LivingController@joinOrders']);
Route::post('salon/unir-cuentas/save', ['as' => 'post-unir-cuentas', 'uses' => 'Restaurant\LivingController@postJoinOrders']);


Route::get('facturas-anular/{token}',['as' => 'anular-facturas', 'uses' => 'Restaurant\LivingController@anular']);
Route::get('facturas-reimpresion/{token}',['as' => 'reimpresion-facturas', 'uses' => 'Restaurant\LivingController@rePrint']);