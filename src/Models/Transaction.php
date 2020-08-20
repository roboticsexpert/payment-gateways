<?php

namespace Roboticsexpert\PaymentGateways\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Transaction
 *
 * @package  Sibche\Domains\Finance\Models
 * @property string id
 * @property integer price
 * @property int user_id
 * @property int invoice_id
 * @property int payment_gateway_id
 * @property boolean paid
 * @property boolean processed
 * @property string authority
 * @property string tracking_code
 * @property string card_number
 * @property array information
 * @property string ip
 * @property \Carbon\Carbon|null paid_at
 * @property \Carbon\Carbon|null processed_at
 * @property \Carbon\Carbon|null created_at
 * @property \Carbon\Carbon|null updated_at
 */
class Transaction extends Model
{
    const MINIMUM_PRICE = 100;
    const MAXIMUM_PRICE = 3000000;
    protected $guarded = [];

    protected $casts = [
        'information' => 'array',
    ];

    protected $dates = ['paid_at', 'processed_at'];


    public function getPaidAttribute()
    {
        return !empty($this->paid_at);
    }

    public function scopePaid($query)
    {
        return $query->whereNotNull('paid_at');
    }

    public function scopeNotPaid($query)
    {
        return $query->whereNull('paid_at');
    }

    public function getProcessedAttribute()
    {
        return !empty($this->processed_at);
    }

    public function scopeProcessed($query)
    {
        return $query->whereNotNull('processed_at');
    }

    public function scopeNotProcessed($query)
    {
        return $query->whereNull('processed_at');
    }

    public function paymentGateway()
    {
        return $this->belongsTo(PaymentGateway::class);
    }
}
