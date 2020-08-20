<?php
/**
 * Created by PhpStorm.
 * User: roboticsexpert
 * Date: 5/14/17
 * Time: 12:26 AM
 */

namespace Roboticsexpert\PaymentGateways\Gateways;

class ZarinpalPaymentGatewaySandbox extends ZarinpalPaymentGateway
{

    public function __construct()
    {
        $this->soapUrl = 'https://sandbox.zarinpal.com/pg/services/WebGate/wsdl';
        $this->payUrl = 'https://sandbox.zarinpal.com/pg/StartPay/';
    }

    public function getType(): string
    {
        return 'ZARINPAL_SANDBOX';
    }
}
