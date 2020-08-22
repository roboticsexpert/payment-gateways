@extends('pg::layouts.app')

@section('content')
    <div class="container">
        <div class="col-md-4 col-md-offset-4">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th colspan="2" class="text-center">
                        تراکنش
                    </th>
                </tr>
                </thead>
                <tr>
                    <th>مبلغ</th>
                    <td>{{\Roboticsexpert\PaymentGateways\Helpers\NumberHelper::formatMoney($transaction->price)}}</td>
                </tr>
                <tr>
                    <th>وضعیت</th>
                    <td>
                        @if($transaction->paid)
                            <span class="label label-success">
                                پراخت شده
                            </span>
                        @else
                            <form method="get"
                                  action="{{route("transactions.pay",['id'=>$transaction->id,'callback'=>$callback])}}">
                                {!! csrf_field() !!}
                                <button type="submit" class="btn btn-success">
                                    پرداخت
                                </button>
                            </form>
                        @endif

                    </td>
                </tr>

            </table>
        </div>
    </div>
@endsection
