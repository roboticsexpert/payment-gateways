<?php
/**
 * Created by PhpStorm.
 * User: roboticsexpert
 * Date: 5/14/17
 * Time: 12:26 AM
 */

namespace Roboticsexpert\PaymentGateways\Gateways;

use Roboticsexpert\PaymentGateways\Interfaces\GatewayConfig;
use Roboticsexpert\PaymentGateways\Interfaces\IGateway;
use Roboticsexpert\PaymentGateways\ValueObjects\GatewayRequestAnswer;
use Roboticsexpert\PaymentGateways\ValueObjects\GatewayRequestUrl;
use Roboticsexpert\PaymentGateways\ValueObjects\GatewayVerifyAnswer;

class ZarinpalPaymentGateway implements IGateway
{

    protected $merchantId;

    //    const SoapUrl = 'https://ir.zarinpal.com/pg/services/WebGate/wsdl';
    //    const SoapUrl = 'https://de.zarinpal.com/pg/services/WebGate/wsdl';
    //    const PayUrl = 'https://www.zarinpal.com/pg/StartPay/';

    protected $soapUrl;
    protected $payUrl;

    public function __construct()
    {
        $this->soapUrl = 'https://ir.zarinpal.com/pg/services/WebGate/wsdl';
        $this->payUrl = 'https://www.zarinpal.com/pg/StartPay/';
    }

    private function createPayUrl($authority)
    {
        return $this->payUrl . $authority . '/ZarinGate';
    }

    /**
     * @param $callbackUrl
     * @param int $price
     * @param $transactionKey
     * @param $description
     * @param null $email
     * @param null $mobile
     *
     * @return bool|GatewayRequestAnswer
     * @throws \SoapFault
     * @throws \Exception
     */
    public function request($callbackUrl, int $price, $transactionKey, $description, ?string $email=null, ?string $mobile=null): GatewayRequestAnswer
    {
        $price = $this->normalizePrice($price);
        $options = array(
            'MerchantID' => $this->merchantId,
            'Amount' => $price,
            'Description' => $description,
            'CallbackURL' => $callbackUrl
        );

        if (!empty($email)) {
            $options['Email'] = $email;
        }

        // URL also Can be https://ir.zarinpal.com/pg/services/WebGate/wsdl
        $client = new \SoapClient($this->soapUrl, array('encoding' => 'UTF-8'));


        $result = $client->PaymentRequest($options);


        $options['authority'] = $result->Authority;
        //Redirect to URL You can do it also by creating a form
        if ($result->Status == 100) {
            return new GatewayRequestAnswer($options, new GatewayRequestUrl($this->createPayUrl($result->Authority)));
        }


        throw new \Exception('something has issue on create payment request');
    }


    /**
     * @param int $price
     * @param $authority
     * @param array $transactionParameters
     *
     * @return GatewayVerifyAnswer
     * @throws \SoapFault
     */
    public function verify(int $price, $authority, $transactionParameters = []): GatewayVerifyAnswer
    {

        $price = $this->normalizePrice($price);
        $client = new \SoapClient($this->soapUrl, array('encoding' => 'UTF-8'));

        $result = $client->PaymentVerification(
            array(
                'MerchantID' => $this->merchantId,
                'Authority' => $authority,
                'Amount' => $price
            )
        );

        return new GatewayVerifyAnswer(in_array($result->Status, [100, 101]), $this->deNormalizePrice($price), $result->RefID);
    }

    /**
     * @param $price
     *
     * @return mixed
     */
    private function normalizePrice($price): int
    {
        return $price;
    }

    private function deNormalizePrice($price): int
    {
        return $price;
    }

    /**
     * @param array $transactionParameters
     * @return mixed|string|null
     */
    public function getAuthorityKey($transactionParameters = []): ?string
    {
        if (isset($transactionParameters['authority'])) {
            return $transactionParameters['authority'];
        }
        return null;
    }

    public function getType(): string
    {
        return 'ZARINPAL';
    }

    /**
     * @return GatewayConfig[]
     */
    public function getConfigLists(): array
    {
        return [
            new GatewayConfig('merchant_id', 'Merchant Id', 'merchant id is required for zarinpal'),
        ];
    }

    public function setConfigs(array $config): void
    {
        $this->merchantId = $config['merchant_id'];
    }
}
