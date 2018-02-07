@extends(config('blog.layout'))
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('blog::categories.index')}}">
            <span class="glyphicon glyphicon-list"> categories</span>
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{route('blog::categories.show',$model->slug)}}">
            <span class="glyphicon glyphicon-list"> {{$model->title}}</span>
        </a>
    </li>
    <li class="breadcrumb-item">Edit</li>
@endsection
@section('content')
    <div class="row">
        <div class='col-md-12'>
            <div class='card'>
                <div class="card-body">
                    @include('blog::forms.category',[
                    'route'=>route('blog::categories.update',$model->slug),
                    'method'=>'PUT'
                    ])
                </div>
            </div>
        </div>
    </div>
@endSection