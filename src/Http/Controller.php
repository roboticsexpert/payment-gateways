<?php

namespace Roboticsexpert\PaymentGateways\Http;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Roboticsexpert\PaymentGateways\Helpers\AlertHelper;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    //TODO improve this structure for alert helper (move to constructure if possible)

    /**
     * @return AlertHelper
     */
    protected function alert()
    {
        /**
         * @var AlertHelper $alertManager
         */
        return app(AlertHelper::class);
    }
}
