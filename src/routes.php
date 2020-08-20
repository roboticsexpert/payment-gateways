<?php

Route::get('payment-gateways/', 'Roboticsexpert\PaymentGateways\Http\PaymentGatewayController@index')->name('payment-gateways.index');
Route::post('payment-gateways/{id}', 'Roboticsexpert\PaymentGateways\Http\PaymentGatewayController@update')->name('payment-gateways.update');
