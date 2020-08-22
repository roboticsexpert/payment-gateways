@extends('pg::layouts.app')

@section('content')
<div class="container">
    <div class="alert alert-info" role="alert">
        <strong>مفهوم وزن:</strong>
        وزن بیشتر به معنای احتمال بیشتر در انتخاب درگاه است،
        <br>
        برای مثال اگر ۲ درگاه فعال داشته باشید که یکی ۵ و دیگری وزن ۱۰ داشته باشد در ۶۶ درصد مواقع درگاه با وزن ۱۰ و با
        ۳۳ درصد مواقع درگاه با وزن ۵ انتخاب میشود
    </div>
    @foreach($models as $model)
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">{{$model->type}}: {{$model->name}}</div>
            </div>
            <div class="panel-body">
                <pre class="text-left">
                    @foreach($model->information as $key=>$information)
                        @if($information)
                            {{$key}} : {{$information}}
                        @endif
                    @endforeach
                </pre>

                <form method="post" class="form-inline"
                      action="{{route('admin.payment.gateways.update',['id'=>$model->id])}}"
                      enctype="multipart/form-data">
                    @csrf
                    <label>
                        نام
                    </label>
                    <input type="text" class="form-control" name="name" value="{{old('name',$model->name)}}">
                    <label>
                        وزن
                    </label>
                    <input type="number" class="form-control" name="weight" value="{{old('weight',$model->weight)}}"/>
                    <label>
                        وضعیت
                    </label>
                    <select name="active" class="form-control">
                        <option value="1" @if($model->active) selected="selected" @endif>فعال</option>
                        <option value="0" @if(!$model->active) selected="selected" @endif>غیرفعال</option>

                    </select>

                    <input type="submit" class="btn btn-success" value="بروزرسانی"/>
                </form>
            </div>
            <div class="clearfix"></div>
        </div>
    @endforeach
</div>
@endsection
