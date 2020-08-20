<?php
/**
 * Created by PhpStorm.
 * User: roboticsexpert
 * Date: 5/27/17
 * Time: 8:28 PM
 */

namespace Roboticsexpert\PaymentGateways\Interfaces;


class GatewayConfig
{
    private $key;
    private $title;
    private $description;

    /**
     * GatewayConfig constructor.
     * @param $key
     * @param $title
     * @param $description
     */
    public function __construct(
        $key,
        $title,
        $description
    )
    {
        $this->key = $key;
        $this->title = $title;
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

}
