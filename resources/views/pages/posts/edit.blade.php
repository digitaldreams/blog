@extends(config('blog.layout.create'))
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('blog::posts.index')}}">Posts</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{route('blog::posts.show',$model->slug)}}">{{$model->title}}</a>
    </li>
@endsection
@section('tools')
    <a href="{{route('blog::posts.create')}}">
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
@section('topright')
    @include('blog::pages.posts.top_right')
@endsection
@section('scripts')
    <script type="text/javascript">
        $('#blog_tags').select2();
    </script>
@endsection