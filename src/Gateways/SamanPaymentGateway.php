<?php
/**
 * Created by PhpStorm.
 * User: roboticsexpert
 * Date: 1/1/19
 * Time: 18:33
 */

namespace Roboticsexpert\PaymentGateways\Gateways;

use Roboticsexpert\PaymentGateways\Interfaces\GatewayConfig;
use Roboticsexpert\PaymentGateways\Interfaces\IGateway;
use Roboticsexpert\PaymentGateways\ValueObjects\GatewayRequestAnswer;
use Roboticsexpert\PaymentGateways\ValueObjects\GatewayRequestUrl;
use Roboticsexpert\PaymentGateways\ValueObjects\GatewayVerifyAnswer;

class SamanPaymentGateway implements IGateway
{
    const SAMAN_PGW_SERVER = 'https://sep.shaparak.ir/Payments/ReferencePayment.asmx?WSDL';
    const SAMAN_REQUEST_URL = 'https://sep.shaparak.ir/MobilePG/MobilePayment';

    /**
     * @var string
     */
    private $terminalId;
    /**
     * @var string
     */
    private $terminalPassword;
    /**
     * @var \SoapClient
     */
    private $soapClient;

    /**
     * SamanPaymentGateway constructor.
     *
     * @param $terminalId
     * @param $terminalPassword
     *
     * @throws \SoapFault
     */
    public function __construct()
    {
        $this->soapClient = new \SoapClient(self::SAMAN_PGW_SERVER);
    }

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
    public function request($callbackUrl, int $price, $transactionKey, $description, $email, $mobile): GatewayRequestAnswer
    {
        return new GatewayRequestAnswer(
            [
                'Amount' => $price * 10,
                'ResNum' => $transactionKey,
                'RedirectURL' => $callbackUrl,
                'MID' => $this->terminalId,
                'CellNumber' => $mobile,
            ],
            new GatewayRequestUrl(
                self::SAMAN_REQUEST_URL, 'post', [
                    'Amount' => $price * 10,
                    'ResNum' => $transactionKey,
                    'RedirectURL' => $callbackUrl,
                    'MID' => $this->terminalId,
                    'CellNumber' => $mobile,
                ]
            )
        );
    }

    /**
     * @param int $price
     * @param $authority
     * @param array $transactionParameters
     *
     * @return GatewayVerifyAnswer
     * @throws \Exception
     */
    public function verify(int $price, $authority, $transactionParameters = []): GatewayVerifyAnswer
    {
        if ((!$this->soapClient)) {
            throw new \Exception("SOAP Client Error");
        }


        if (!isset($transactionParameters['RefNum'])) {
            return new GatewayVerifyAnswer(
                false,
                $price * 10,
                null,
                null
            );
        }
        $referenceNumber = $authority;
        $res = $this->soapClient->VerifyTransaction($referenceNumber, $this->terminalId);

        return new GatewayVerifyAnswer(
            $res > 0 && $res == $price * 10,
            $price * 10,
            $transactionParameters['TraceNo'],
            $transactionParameters['SecurePan']
        );
    }

    public function getAuthorityKey($transactionParameters = []): ?string
    {
        if (isset($transactionParameters['RefNum'])) {
            return $transactionParameters['RefNum'];
        }
        return null;
    }

    public function getType(): string
    {
        return 'SAMAN';
    }

    /**
     * @return GatewayConfig[]
     */
    public function getConfigLists(): array
    {
        return [
            new GatewayConfig('terminal_id', 'Terminal Id', 'Terminal Id of saman'),
            new GatewayConfig('terminal_password', 'Terminal Password', 'Terminal password of saman'),
        ];
    }

    public function setConfigs(array $config): void
    {
        $this->terminalId = $config['terminal_id'];
        $this->terminalPassword = $config['terminal_password'];
    }

}
