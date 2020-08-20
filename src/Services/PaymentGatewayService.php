<?php
/**
 * Created by PhpStorm.
 * User: roboticsexpert
 * Date: 5/13/17
 * Time: 2:10 PM
 */

namespace Roboticsexpert\PaymentGateways\Services;


use Roboticsexpert\PaymentGateways\Interfaces\IGateway;
use Roboticsexpert\PaymentGateways\Models\PaymentGateway;

class PaymentGatewayService
{
    /**
     * @var IGateway[]
     */
    private array $paymentGatewayInstances;

    /**
     * @param Transaction $transaction
     *
     * @return IGateway
     */
    public function getGatewayServiceFromPaymentGateway(PaymentGateway $paymentGateway)
    {
        return $this->getGatewayServiceForPaymentGateway($paymentGateway);
    }

    /**
     * PaymentGatewayService constructor.
     * @param array $paymentGateways
     */
    public function __construct(array $paymentGateways)
    {
        $this->paymentGatewayInstances = $paymentGateways;
    }

    public function getGatewayServiceForPaymentGateway(PaymentGateway $paymentGateway)
    {
        $paymentGatewayInstance = $this->getPaymentInstanceByType($paymentGateway->type);
        $paymentGatewayInstance->setConfigs($paymentGateway->information);
        return $paymentGatewayInstance;
    }

    private function getPaymentInstanceByType(string $type)
    {
        foreach ($this->paymentGatewayInstances as $paymentGatewayInstance) {
            if ($paymentGatewayInstance->getType() == $type) {
                return $paymentGatewayInstance;
            }
        }
        return null;
    }


    public function getPaymentGatewayForPayment()
    {

        $paymentGateways = PaymentGateway::where('active', true)->get();

        $array = [];

        foreach ($paymentGateways as $paymentGateway) {
            for ($i = 0; $i < $paymentGateway->weight; $i++) {
                $array[] = $paymentGateway;
            }
        }

        $key = array_rand($array);

        return $array[$key];
    }
}
