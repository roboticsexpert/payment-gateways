<?php

Route::namespace('Roboticsexpert\\PaymentGateways\\Http')->group(function () {
    Route::get('payment-gateways/', 'PaymentGatewayController@index')->name('payment-gateways.index');
    Route::post('payment-gateways/{id}', 'Roboticsexpert\PaymentGateways\Http\PaymentGatewayController@update')->name('payment-gateways.update');


    Route::prefix('transactions')->group(function () {
        Route::any('notAllowed', 'TransactionController@notAllowed')->name('pg::transactions.notAllowed');
        Route::get('{id}', 'TransactionController@show')->name('pg::transactions.show');
        Route::get('{id}/pay', 'TransactionController@pay')->name('pg::transactions.pay');
        Route::any('{id}/verify', 'TransactionController@verify')->name('pg::transactions.verify');
    });
});
