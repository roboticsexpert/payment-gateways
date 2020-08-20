<?php

namespace Roboticsexpert\PaymentGateways\Http;

use Illuminate\Http\Request;
use Roboticsexpert\PaymentGateways\Models\PaymentGateway;

class PaymentGatewayController extends Controller
{
    public function index()
    {
        $models = PaymentGateway::get();
        return view('pg::paymentGateways.index', ['models' => $models]);
    }

    public function update($id, Request $request)
    {
        $model = PaymentGateway::findOrFail($id);

        $model->active = $request->input('active');
        $model->weight = $request->input('weight');
        $model->name = $request->input('name');

        $model->save();

        $this->alert()->success('Success', "Operation Successfully Completed");
        return redirect()->route('roboticsexpert.payment-gateways.index');
    }
}
