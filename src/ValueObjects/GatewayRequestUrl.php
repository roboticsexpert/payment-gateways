<?php


namespace Roboticsexpert\PaymentGateways\ValueObjects;


class GatewayRequestUrl
{
    private $url;
    private $method;
    /**
     * @var array
     */
    private $params;

    /**
     * GatewayRequestUrl constructor.
     *
     * @param $url
     * @param $method
     * @param array $params
     */
    public function __construct($url, $method = 'get', array $params = [])
    {
        $this->url = $url;
        $this->method = $method;
        $this->params = $params;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }
}
