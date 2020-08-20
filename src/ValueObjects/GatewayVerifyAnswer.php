<?php
/**
 * Created by PhpStorm.
 * User: roboticsexpert
 * Date: 7/7/17
 * Time: 10:59 PM
 */

namespace Roboticsexpert\PaymentGateways\ValueObjects;

class GatewayVerifyAnswer
{
    /**
     * @var string
     */
    private $trackingCode;

    /**
     * @var int
     */
    private $price;

    /**
     * @var bool
     */
    private $success;

    private $cardNumber;

    /**
     * GatewayVerifyAnswer constructor.
     *
     * @param bool $success
     * @param int $price
     * @param string|null $trackingCode
     * @param string|null $cardNumber
     */
    public function __construct(bool $success, int $price, ?string $trackingCode = null, ?string $cardNumber = null)
    {
        $this->success = $success;
        $this->price = $price;
        $this->trackingCode = $trackingCode;
        $this->cardNumber = $cardNumber;
    }

    /**
     * @return string
     */
    public function getTrackingCode()
    {
        return $this->trackingCode;
    }

    /**
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @return mixed
     */
    public function getCardNumber()
    {
        return $this->cardNumber;
    }
}
