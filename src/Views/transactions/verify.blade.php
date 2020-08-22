@extends('pg::layouts.app')

@section('content')

    <div class="transaction">
        <div class="container">
            <div>
                @if($transaction->paid)
                    <i class="icon-success fa fa-check" aria-hidden="true"></i>
                    <div class="success">پرداخت شما با موفقیت انجام شد.</div>
                    <p class="detail">شماره تراکنش
                        {{$transaction->tracking_code}}
                    </p>

                @else
                    <div class="error">مشکلی در پرداخت روی داده است.</div>
                @endif

{{--                <a href="#" class="back-to-app">بازگشت به برنامه</a>--}}

            </div>
        </div>
    </div>
@endsection
