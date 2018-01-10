@extends(config('blog.layout'))
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('blog::categories.index')}}">Categories</a> </li>
    <li class="breadcrumb-item"><a href="{{route('blog::categories.show',$model->slug)}}">{{$model->title}}</a> </li>
    <li class="breadcrumb-item">Edit</li>
@endsection
@section('tools')
    <a href="{{route('blog::categories.create')}}">
        <span class="glyphicon glyphicon-plus"></span>
    </a>
@endsection
@section('content')
    <div class="row">
        <div class='col-md-12'>
            <div class='panel panel-default'>
                           <div class="panel-body">
                    @include('blog::forms.category',[
                    'route'=>route('blog::categories.update',$model->slug),
                    'method'=>'PUT'
                    ])
                </div>
            </div>
        </div>
    </div>
@endSection