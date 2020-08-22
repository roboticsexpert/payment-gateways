<?php


namespace Roboticsexpert\PaymentGateways\Gateways;


use Roboticsexpert\PaymentGateways\Interfaces\GatewayConfig;
use Roboticsexpert\PaymentGateways\Interfaces\IGateway;
use Roboticsexpert\PaymentGateways\ValueObjects\GatewayRequestAnswer;
use Roboticsexpert\PaymentGateways\ValueObjects\GatewayRequestUrl;
use Roboticsexpert\PaymentGateways\ValueObjects\GatewayVerifyAnswer;
use Sibche\Helpers\MobileHelper;
use SoapClient;

class MellatPaymentGateway implements IGateway
{
    private $terminalId;
    private $username;
    private $password;
    private $soapClient;

    const WSDL = 'https://bpm.shaparak.ir/pgwchannel/services/pgw?wsdl';
    const PAY_URL = 'https://bpm.shaparak.ir/pgwchannel/startpay.mellat';

    /**
     * MellatGateway constructor.
     *
     * @param  $terminalId
     * @param  $username
     * @param  $password
     *
     * @throws \SoapFault
     */
    public function __construct()
    {
        $this->soapClient = new SoapClient(self::WSDL);
    }

    /**
     * @param callbackUrl
     * @param int $price
     * @param transactionId
     * @param description
     * @param email
     * @param mobile
     *
     *
     * @return GatewayRequestAnswer
     * @throws \Exception
     */
    public function request($callbackUrl, int $price, $transactionId, $description, ?string $email=null, ?string $mobile=null): GatewayRequestAnswer
    {

        if ($price && $price >= 100 && $transactionId) {


            $price = $price * 10;
            $localDate = date("Ymd");
            $localTime = date("His");

            $parameters = [
                'terminalId' => $this->terminalId,
                'userName' => $this->username,
                'userPassword' => $this->password,
                'orderId' => $transactionId,
                'amount' => $price,
                'localDate' => $localDate,
                'localTime' => $localTime,
                'additionalData' => $description,
                'callBackUrl' => $callbackUrl,
                'payerId' => $mobile,
                'mobileNo' => MobileHelper::getInternationalMobileNumber($mobile)
            ];


            $request = $this->soapClient->bpPayRequest($parameters);

            $result = explode(',', $request->return);

            if ($result[0] == "0") {

                return new GatewayRequestAnswer(
                    [
                        'RefId' => $result[1],
                        'res_code' => $result[0],
                        'localDate' => $localDate,
                        'localTime' => $localTime,
                        'callBackUrl' => $callbackUrl,
                        'terminalId' => $this->terminalId,
                        'orderId' => $transactionId,
                        'amount' => $price,
                    ],
                    new GatewayRequestUrl(
                        self::PAY_URL, 'POST', [
                            'RefId' => $result[1],
                            'MobileNo' => MobileHelper::getInternationalMobileNumber($mobile)
                        ]
                    )
                );

            } else {
                throw new \Exception("Mellat payment request Error Code : " . $result[0]);
            }
        }
        throw new \Exception("price should be greater than 100 ");
    }

    /**
     * @param int $price
     * @param  $authority
     * @param array $transactionParameters
     *
     * @return GatewayVerifyAnswer
     */
    public function verify(int $price, $authority, $transactionParameters = []): GatewayVerifyAnswer
    {

        if (!array_key_exists('SaleOrderId', $transactionParameters)) {
            return new GatewayVerifyAnswer(false, $price, null, null);
        }


        if (!array_key_exists('orderId', $transactionParameters)) {
            return new GatewayVerifyAnswer(false, $price, null, null);
        }


        if (!array_key_exists('SaleReferenceId', $transactionParameters)) {
            return new GatewayVerifyAnswer(false, $price, null, null);
        }


        if (!array_key_exists('RefId', $transactionParameters) || $transactionParameters['RefId'] != $authority) {
            return new GatewayVerifyAnswer(false, $price, null, null);
        }


        $parameters = [
            'terminalId' => $this->terminalId,
            'userName' => $this->username,
            'userPassword' => $this->password,
            'orderId' => $transactionParameters['orderId'],
            'saleOrderId' => $transactionParameters['SaleOrderId'],
            'saleReferenceId' => $transactionParameters['SaleReferenceId'],
        ];

        try {

            $status = in_array($this->soapClient->bpVerifyRequest($parameters)->return, ['0', '43']) &&
                in_array($this->soapClient->bpSettleRequest($parameters)->return, ['0', '45']);

            return new GatewayVerifyAnswer($status, $price, $authority, $transactionParameters['CardHolderPan']);

        } catch (\Exception $exception) {
        }
        return new GatewayVerifyAnswer(false, $price, $authority);

    }

    public function getAuthorityKey($transactionParameters = []): ?string
    {
        if (isset($transactionParameters['RefId'])) {

            return $transactionParameters['RefId'];

        }
        return null;
    }

    public function revers(array $parameters)
    {
        //Not implemented
        //$this->soapClient->bpReversalRequest($parameters);
    }

    public function getType(): string
    {
        return 'MELLAT';
    }

    public function getConfigLists(): array
    {
        return [
            new GatewayConfig('terminal_id', 'Terminal Id', ''),
            new GatewayConfig('username', 'Username', ''),
            new GatewayConfig('password', 'Password', ''),
        ];
    }

    public function setConfigs(array $config): void
    {
        $this->terminalId = $config['terminal_id'];
        $this->username = $config['username'];
        $this->password = $config['password'];
    }
}



