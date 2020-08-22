<?php

namespace Roboticsexpert\PaymentGateways\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PaymentGateway
 * @property int id
 * @property string title
 * @property int weight
 * @property string type
 * @property array information
 */
class PaymentGateway extends Model
{
    protected $casts = [
        'information' => 'json'
    ];

    protected $attributes=[
        'weight'=>1
    ];

    public function scopeActive(Builder $query)
    {
        return $query->where('active', '=', true);
    }
}
