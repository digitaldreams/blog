@extends(config('blog.layout'))
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('blog::posts.index')}}">Posts</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{route('blog::posts.show',$model->slug)}}">{{$model->title}}</a>
    </li>
@endsection
@section('header')
    <i class="fa fa-pencil-alt text-muted" style="font-size: 18px"></i> {{$model->title}}
@endsection
@section('tools')
    <div class="btn-group btn-group-sm">
        <button type="submit" form="postForm" class="btn btn-primary"><i class="fa fa-save"></i> Save
        </button>
        <a class="btn btn-secondary" href="{{route('blog::posts.create')}}">
            <i class="fa fa-plus"></i>
        </a>
    </div>


@endsection
@section('content')
    <div class="row">
        <div class='col-md-9'>
            <div class='panel panel-default'>

                <div class="panel-body">
                    @include('blog::forms.post',[
                    'route'=>route('blog::posts.update',$model->slug),
                    'method'=>'PUT'
                    ])
                </div>
            </div>
        </div>
        <div class="col-md-3">
            @include('blog::pages.posts.top_right')
        </div>
    </div>
@endSection

@section('script')
    @include('blog::includes.popoverModal')
    @include('blog::includes.tooltipModal')
    @include('blog::includes.summernoteImageInsertModal')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.textcomplete/1.8.4/jquery.textcomplete.min.js"></script>
    <script type="text/javascript" src="{{asset('js/bootsum.js')}}"></script>
    <script type="text/javascript">

        $('#blog_tags').select2();
    </script>
@endsection
