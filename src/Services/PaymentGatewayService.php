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

    public function getPaymentGatewayById(int $id)
    {
        return PaymentGateway::find($id);
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

        if (empty($array)) {
            throw new \Exception('no payment gateway is active');
        }
        $key = array_rand($array);

        return $array[$key];
    }
}
