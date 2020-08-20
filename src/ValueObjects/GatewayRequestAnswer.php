<?php
/**
 * Created by PhpStorm.
 * User: roboticsexpert
 * Date: 7/7/17
 * Time: 10:59 PM
 */

namespace Roboticsexpert\PaymentGateways\ValueObjects;

class GatewayRequestAnswer
{
    /**
     * @var GatewayRequestUrl
     */
    private $gatewayRequestUrl;
    /**
     * @var array
     */
    private $transactionParameters;
    /**
     * @var string
     */


    /**
     * GatewayRequestAnswer constructor.
     *
     * @param array             $transactionParameters
     * @param GatewayRequestUrl $gatewayRequestUrl
     */
    public function __construct(array $transactionParameters, GatewayRequestUrl $gatewayRequestUrl)
    {
        $this->gatewayRequestUrl = $gatewayRequestUrl;
        $this->transactionParameters = $transactionParameters;
    }

    /**
     * @return GatewayRequestUrl
     */
    public function getGatewayRequestUrl(): GatewayRequestUrl
    {
        return $this->gatewayRequestUrl;
    }

    /**
     * @return array
     */
    public function getTransactionParameters(): array
    {
        return $this->transactionParameters;
    }
}

