@inject('alertService',"Roboticsexpert\PaymentGateways\Helpers\AlertHelper")

<div class="alerts" style="position: fixed;z-index:10000;right:0;top: 0;margin-top:85px;margin-right:20px;width: auto;">
    @foreach($alertService->getAlerts() as $alert)
        <div class="row">
            <div class="alert alert-{{$alert->type}} alert-dismissible" role="alert" style="display: inline-block;">
                {{$alert->message}}
            </div>
        </div>
    @endforeach
</div>


<script>
    $(document).ready(function () {
        $('.alerts .alert').delay(2000).slideUp(100);
    });
</script>
