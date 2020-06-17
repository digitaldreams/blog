@extends(config('blog.layout'))
@section('breadcrumb')

    <li class="breadcrumb-item">
        <a href="{{route('blog::categories.index')}}">Categories</a>
    </li>
    <li class="breadcrumb-item">
        {{$record->title}}
    </li>
@endsection
@section('header')
    <i class="fa fa-pencil-alt text-muted" style="font-size: 18px"></i> Posts
@endsection
@section('tools')
    <div class="btn-group">
        @can('create',\Blog\Models\Category::class)
            <a class="btn btn-secondary" href="{{route('blog::categories.create')}}"> <i class="fa fa-plus"></i> New Category</a>
        @endcan
            @can('create',\Blog\Models\Post::class)
                <a class="btn btn-secondary" href="{{route('blog::posts.create')}}"> <i class="fa fa-plus"></i> New Post</a>
            @endcan
    </div>
@endsection
@section('content')

    @if($posts->count()>0)
        <div class="row">
            @foreach($posts as $post)
                <div class="col-md-4 col-sm-6">
                    @include('blog::cards.post',['record'=>$post])
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-warning">No posts found</div>
    @endif

    <div class='row'>
        <div class="col-sm-12">
            {{{$posts->render()}}}
        </div>
    </div>
@endSection
