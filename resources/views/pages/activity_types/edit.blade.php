@extends(config('blog.layout'))
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('blog::types.index')}}">activity types</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{route('blog::types.show',$model->id)}}">{{$model->name}}</a>
    </li>
    <li class="breadcrumb-item">
        Edit
    </li>
@endsection

@section('tools')
    <a href="{{route('blog::types.create')}}">
        <span class="fa fa-plus"></span> activity type
    </a>
@endsection

@section('content')
    <div class="row">
        <div class='col-md-12'>
            <div class='card'>
                <div class="card-body">
                    @include('packages.blog.resources.views.forms.activity_type',[
                    'route'=>route('blog::types.update',$model->id),
                    'method'=>'PUT'
                    ])
                </div>
            </div>
        </div>
    </div>
@endSection
