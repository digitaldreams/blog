@extends(config('blog.layout'))
@section('content')
<div class="row">
    <div class='col-md-12'>
        <div class='panel panel-default'>
            <div class='panel-heading'>
                <div class="row">
                    <div class="col-sm-8">
                        <h4>
                            Edit {{$model->id}}
                        </h4>
                    </div>
                    <div class="col-sm-4 text-right">
                        <a href="{{route('categories.create')}}">
                            <span class="glyphicon glyphicon-plus"></span> categories
                        </a>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                @include('forms.category',[
                'route'=>route('blog::categories.update',$model->id),
                'method'=>'PUT'
                ])
            </div>
        </div>
    </div>
</div>
@endSection