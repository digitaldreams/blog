@extends(config('blog.layout'))
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('blog::posts.index')}}">Posts</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{route('blog::posts.show',$model->slug)}}">{{$model->title}}</a>
    </li>
@endsection
@section('tools')
    <a href="{{route('posts.create')}}">
        <span class="fa fa-plus"></span>
    </a>
@endsection
@section('content')
    <div class="row">
        <div class='col-md-12'>
            <div class='panel panel-default'>

                <div class="panel-body">
                    @include('blog::forms.post',[
                    'route'=>route('blog::posts.update',$model->slug),
                    'method'=>'PUT'
                    ])
                </div>
            </div>
        </div>
    </div>
@endSection
@section('styles')
    <link href='{{asset('summernote/summernote-bs4.css')}}' rel='stylesheet' type='text/css'/>
@endsection

@section('scripts')
    <script src="{{asset('summernote/summernote-bs4.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function (e) {
            $('#summernote').summernote();
        })
    </script>
@endsection