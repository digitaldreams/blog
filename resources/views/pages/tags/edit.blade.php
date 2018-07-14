@extends(config('blog.layout.show'))
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('blog::tags.index')}}">
            <span class="glyphicon glyphicon-list"> tags</span>
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{route('blog::tags.show',$model->slug)}}">
            <span class="glyphicon glyphicon-list"> {{$model->name}}</span>
        </a>
    </li>
    <li class="breadcrumb-item">Edit</li>
@endsection
@section('content')
    <div class="row">
        <div class='col-md-12'>
            <div class='card'>
                <div class="card-body">
                    @include('blog::forms.tag',[
                    'route'=>route('blog::tags.update',$model->slug),
                    'method'=>'PUT'
                    ])
                </div>
            </div>
        </div>
    </div>
@endSection