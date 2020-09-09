<?php

namespace Roboticsexpert\PaymentGateways\Http;

use Illuminate\Http\Request;
use Roboticsexpert\PaymentGateways\Models\Transaction;
use Roboticsexpert\PaymentGateways\Services\PaymentGatewayService;
use Roboticsexpert\PaymentGateways\Services\TransactionService;

class TransactionController extends Controller
{

    /**
     * @var TransactionService
     */
    private TransactionService $transactionService;
    /**
     * @var PaymentGatewayService
     */
    private PaymentGatewayService $paymentGatewayService;

    public function __construct(TransactionService $transactionService, PaymentGatewayService $paymentGatewayService)
    {

        $this->transactionService = $transactionService;
        $this->paymentGatewayService = $paymentGatewayService;
    }

    public function show($id, Request $request)
    {
        $transaction = $this->transactionService->get($id);
        if (!$transaction) {
            abort(404);
        }

        $callback = $request->input('callback');
        return view('pg::transactions.show', compact('transaction', 'callback'));
    }


    public function pay($id, Request $request)
    {
        //TODO fix this shit and move to service
        /**
         * @var Transaction $transaction
         */
        $transaction = $this->transactionService->getUnpaid($id);


        if (!$transaction) {
            abort(404);
        }

        //this is for disable credit increase
        /*if (!$transaction->invoice_id) {
            return redirect()->route('pg::transactions.notAllowed');
        }*/

//        $autoInvoicePay = (boolean)$request->input('autoInvoicePay', false);
//
//        if (!$autoInvoicePay && $transaction->invoice_id) {
//            $transaction->invoice_id = null;
//            $transaction->save();
//        }

        $callback = $request->input('callback');
        $successCallback = $request->input('successCallback');
        $failureCallback = $request->input('failureCallback');

        $gateway = $this->transactionService->getGatewayServiceForTransaction($transaction);


        $routeParams = ['id' => $transaction->id];
        if ($callback) {
            $routeParams['callback'] = $callback;
        }
        if ($successCallback) {
            $routeParams['successCallback'] = $successCallback;
        }
        if ($failureCallback) {
            $routeParams['failureCallback'] = $failureCallback;
        }

        $answer = $gateway->request(url(route('pg::transactions.verify', $routeParams)), $transaction->price, $transaction->id, 'افزایش اعتبار');
        if (!$answer) {
            throw new \Exception('payment gateway not working ! :(');
        }

        $transaction->information = $answer->getTransactionParameters();
        $transaction->save();


        return view('pg::transactions.redirect', ['gatewayRequestUrl' => $answer->getGatewayRequestUrl()]);
    }

    public function verify($id, Request $request)
    {
        //TODO fix this shit and move to service
        /**
         * @var Transaction $transaction
         */

        $transaction = $this->transactionService->getUnpaid($id);

        if (!$transaction) {
            abort(404);
        }


        $requestParams = $request->all();

        $paid = $this->transactionService->pay($transaction, $requestParams);

        $successCallback = $request->input('successCallback', $request->input('callback'));
        $failureCallback = $request->input('failureCallback', $request->input('callback'));


        if ($paid) {
            if ($successCallback) {
                return redirect()->to($successCallback);
            }
        } else {
            if (!empty($failureCallback)) {
                return redirect()->to($failureCallback);
            }
        }

        return view('pg::transactions.verify', compact('transaction'));
    }

    public function notAllowed()
    {
        return view('pg::transactions.notAllowed');
    }
}
