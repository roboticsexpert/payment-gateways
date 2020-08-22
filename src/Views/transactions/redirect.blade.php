@extends('pg::layouts.app')

@section('content')


    <div class="container">
        <div class="text-center center-block">

            <div class="loader">Loading...</div>

            <h4>
                درحال انتقال به درگاه بانک
            </h4>

            <br>
            <small>
                درصورتی که به صورت اتوماتیک منتقل نشدید لینک زیر رو بزنید
            </small>

            <br>
            <br>
            <br>
            <form method="{{$gatewayRequestUrl->getMethod()}}" action="{{$gatewayRequestUrl->getUrl()}}"
                  id="transaction-form">
                @foreach($gatewayRequestUrl->getParams() as $key=>$value)
                    <input type="hidden" name="{{$key}}" value="{{$value}}"/><br>
                @endforeach

                <input type="submit" class='btn btn-success' name="submit_payment" value="همینک مرا منتقل کن"/>
            </form>
        </div>
    </div>



@endsection

@push('scripts')
    <script>
      $(document).ready(function () {
        setTimeout(function()
        {
          $('#transaction-form').submit();
        },2500);

      });
    </script>
@endpush

@push('styles')
    <style>
        .loader,
        .loader:before,
        .loader:after {
            border-radius: 50%;
            width: 2.5em;
            height: 2.5em;
            -webkit-animation-fill-mode: both;
            animation-fill-mode: both;
            -webkit-animation: load7 1.8s infinite ease-in-out;
            animation: load7 1.8s infinite ease-in-out;
        }

        .loader {
            color: #32a6ec;
            font-size: 10px;
            margin: 80px auto;
            position: relative;
            text-indent: -9999em;
            -webkit-transform: translateZ(0);
            -ms-transform: translateZ(0);
            transform: translateZ(0);
            -webkit-animation-delay: -0.16s;
            animation-delay: -0.16s;
        }

        .loader:before,
        .loader:after {
            content: '';
            position: absolute;
            top: 0;
        }

        .loader:before {
            left: -3.5em;
            -webkit-animation-delay: -0.32s;
            animation-delay: -0.32s;
        }

        .loader:after {
            left: 3.5em;
        }

        @-webkit-keyframes load7 {
            0%,
            80%,
            100% {
                box-shadow: 0 2.5em 0 -1.3em;
            }
            40% {
                box-shadow: 0 2.5em 0 0;
            }
        }

        @keyframes load7 {
            0%,
            80%,
            100% {
                box-shadow: 0 2.5em 0 -1.3em;
            }
            40% {
                box-shadow: 0 2.5em 0 0;
            }
        }

    </style>
@endpush
