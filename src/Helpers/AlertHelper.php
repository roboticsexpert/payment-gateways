<?php
/**
 * Created by PhpStorm.
 * User: roboticsexpert
 * Date: 9/14/18
 * Time: 10:57 PM
 */

namespace Roboticsexpert\PaymentGateways\Helpers;

class AlertHelper
{

    /**
     * @param string $title
     * @param string $message
     * @param string $type
     */
    public function alert(string $title, string $message, string $type)
    {
        $this->storeAlert($this->createAlert($title, $message, $type));
    }


    /**
     * @param string $title
     * @param string $message
     */
    public function success(string $title="Success", string $message="Operation Successfully Completed")
    {
        $this->alert($title, $message, Alert::TYPE_SUCCESS);
    }

    /**
     * @param string $title
     * @param string $message
     */
    public function danger(string $title="Error", string $message="Operation Failed")
    {
        $this->alert($title, $message, Alert::TYPE_DANGER);
    }

    /**
     * @param string $title
     * @param string $message
     */
    public function info(string $title, string $message)
    {
        $this->alert($title, $message, Alert::TYPE_INFO);
    }

    /**
     * @param string $title
     * @param string $message
     */
    public function warning(string $title, string $message)
    {
        $this->alert($title, $message, Alert::TYPE_WARNING);
    }

    /**
     * @param string $title
     * @param string $message
     */
    public function error(string $title, string $message)
    {
        //TODO if there is some error alert ... change this implementation
        $this->danger($title, $message);
    }

    /**
     * @param string $title
     * @param string $message
     * @param string $type
     *
     * @return \Sibche\Helpers\Alert
     */
    private function createAlert(string $title, string $message, string $type)
    {
        return new Alert(__($title), __($message), $type);
    }

    /**
     * @param \Sibche\Helpers\Alert $alert
     */
    private function storeAlert(Alert $alert)
    {
        session()->push('alerts', $alert->getAttributes());
    }


    public function getAlerts()
    {

        $alertsArray = session()->pull('alerts', []);


        $alerts = [];
        foreach ($alertsArray as $alertArray) {
            $alerts[] = new Alert($alertArray['title'], $alertArray['message'], $alertArray['type']);
        }

        return $alerts;
    }
}

class Alert
{
    const TYPE_DANGER = 'danger';
    const TYPE_ERROR = 'error';
    const TYPE_SUCCESS = 'success';
    const TYPE_WARNING = 'warning';
    const TYPE_INFO = 'info';

    /**
     * @var string
     */
    public $title;
    /**
     * @var string
     */
    public $message;
    /**
     * @var string
     */
    public $type;

    /**
     * Alert constructor.
     *
     * @param string $title
     * @param string $message
     * @param string $type
     */
    public function __construct(string $title, string $message, string $type)
    {
        $this->title = $title;
        $this->message = $message;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }


    public function getAttributes()
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'type' => $this->type,
        ];
    }
}
