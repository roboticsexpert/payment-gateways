<?php
/**
 * Created by PhpStorm.
 * User: roboticsexpert
 * Date: 9/8/18
 * Time: 3:53 AM
 */

namespace Roboticsexpert\PaymentGateways\Services;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Roboticsexpert\PaymentGateways\Models\Transaction;

/**
 * Class TransactionService
 * @package Roboticsexpert\PaymentGateways\Services
 */
class TransactionService
{
    /**
     * @var PaymentGatewayService
     */
    private PaymentGatewayService $paymentGatewayService;


    /**
     * TransactionService constructor.
     * @param PaymentGatewayService $paymentGatewayService
     */
    public function __construct(PaymentGatewayService $paymentGatewayService)
    {
        $this->paymentGatewayService = $paymentGatewayService;
    }

    public function redirectForPayment(Transaction $transaction, ?string $successCallback = null, ?string $failedCallback = null)
    {
        return redirect()->route('pg::transactions.pay', ['id' => $transaction->id, 'successCallback' => $successCallback, 'failureCallback' => $failedCallback]);
    }

    /**
     * @param int $userId
     * @param int $price
     * @param int|null $invoiceId
     * @return Transaction
     */
    public function create(int $userId, int $price, ?int $invoiceId = null)
    {
        $transaction = new Transaction();
        $transaction->user_id = $userId;

        $transaction->invoice_id = $invoiceId;

        $transaction->price = $price;

        $transaction->ip = Request::ip();

        $paymentGateway = $this->paymentGatewayService->getPaymentGatewayForPayment();

        $transaction->paymentGateway()->associate($paymentGateway);

        $transaction->save();
        return $transaction;
    }


    /**
     * @param string $id
     * @return ?Transaction
     */
    public function get(int $id)
    {
        return Transaction::find($id);
    }


    /**
     * @param string $id
     * @return ?Transaction
     */
    public function getPaidTransacionByInvoiceId(int $id)
    {
        return Transaction::whereNotNull('paid_at')->where('invoice_id', $id)->first();
    }
    
     /**
     * @param string $id
     * @return ?Transaction
     */
    public function getTransacionByInvoiceId(int $id)
    {
        return Transaction::where('invoice_id', $id)->first();
    }


    /**
     * @param string $id
     * @return ?Transaction
     */
    public function getUnpaid(string $id)
    {
        return Transaction::whereKey($id)->whereNUll('paid_at')->first();
    }

    /**
     * @param string $id
     * @param string $userId
     * @return ?Transaction
     */
    public function getForUser(string $id, string $userId)
    {
        return Transaction::where("id", $id)->where('user_id', $userId)->first();
    }


    /**
     * @param string $userId
     * @return mixed
     */
    public function getPaginateForUser(string $userId)
    {
        return Transaction::where('user_id', $userId)->orderBy('created_at', 'DESC')->paginate();
    }

    public function getPaymentGatewayForTransaction(Transaction $transaction)
    {
        return $this->paymentGatewayService->getPaymentGatewayById($transaction->payment_gateway_id);

    }

    public function getGatewayServiceForTransaction(Transaction $transaction)
    {
        $paymentGateway = $this->getPaymentGatewayForTransaction($transaction);
        return $this->paymentGatewayService->getGatewayServiceForPaymentGateway($paymentGateway);
    }

    /**
     * @param Transaction $transaction
     * @param array $requestParams
     * @return bool|mixed
     */
    public function pay(Transaction $transaction, array $requestParams = [])
    {
        if ($transaction->paid) {
            return true;
        }

        $gatewayService = $this->getGatewayServiceForTransaction($transaction);

        $transaction->information = array_merge($requestParams, $transaction->information);

        if (!$transaction->authority) {
            $transaction->authority = $gatewayService->getAuthorityKey($transaction->information);
        }

        $transaction->save();

        $gatewayVerifyAnswer = $gatewayService->verify($transaction->price, $transaction->authority, $transaction->information);

        $isSuccessful = false;
        if ($gatewayVerifyAnswer->isSuccess()) {
            $isSuccessful = DB::transaction(
                function () use (&$transaction, $gatewayVerifyAnswer) {
                    if (Transaction::where('authority', $transaction->authority)->where('id', '!=', $transaction->id)->count() > 0) {
                        return false;
                    }

                    $transaction->refresh();

                    if ($transaction->paid) {
                        return true;
                    }

                    $transaction->paid_at = new Carbon();
                    $transaction->card_number = $gatewayVerifyAnswer->getCardNumber();
                    $transaction->tracking_code = $gatewayVerifyAnswer->getTrackingCode();
                    $transaction->save();

                    return true;
                }
            );

        }

        return $isSuccessful;
    }
}
