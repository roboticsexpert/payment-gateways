<?php
/**
 * Created by PhpStorm.
 * User: roboticsexpert
 * Date: 5/27/17
 * Time: 8:28 PM
 */

namespace Roboticsexpert\PaymentGateways\Interfaces;

use Roboticsexpert\PaymentGateways\ValueObjects\GatewayRequestAnswer;
use Roboticsexpert\PaymentGateways\ValueObjects\GatewayVerifyAnswer;

interface IGateway
{

    /**
     * @param $callbackUrl
     * @param int $price
     * @param $transactionKey
     * @param $description
     * @param $email
     * @param $mobile
     *
     * @return GatewayRequestAnswer
     */
    public function request($callbackUrl, int $price, $transactionKey, $description, $email, $mobile): GatewayRequestAnswer;

    /**
     * @param int $price
     * @param $authority
     * @param array $transactionParameters
     *
     * @return GatewayVerifyAnswer
     */
    public function verify(int $price, $authority, $transactionParameters = []): GatewayVerifyAnswer;

    /**
     * @param array $transactionParameters
     * @return string|null
     */
    public function getAuthorityKey($transactionParameters = []): ?string;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return array
     */
    public function getConfigLists(): array;

    public function setConfigs(array $config): void;
}
