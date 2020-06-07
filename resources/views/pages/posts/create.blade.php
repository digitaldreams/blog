@extends(config('blog.layout'))
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('blog::posts.index')}}">
            <span class=""> posts</span>
        </a>
    </li>
    <li class="breadcrumb-item">
        Create
    </li>
@endsection
@section('header')
  <i class="fa fa-pencil-alt text-muted" style="font-size: 18px"></i> Create New Post
@endsection
@section('tools')
    <div class="btn btn-group btn-group-sm">
        @can('create',\Blog\Models\Category::class)
            <a class="btn btn-secondary" href="{{route('blog::categories.create')}}">
                <i class="fa fa-plus"></i> New Category
            </a>
        @endcan
        @can('create',\Blog\Models\Tag::class)
            <a class="btn btn-secondary" href="{{route('blog::tags.create')}}">
                <i class="fa fa-plus"></i> New Tag
            </a>
        @endcan
        <button type="submit" form="postForm" class="btn btn-primary"><i class="fa fa-save"></i> Save
        </button>
    </div>

@endsection
@section('content')
    <div class="row">
        <div class='col-md-9'>
            <div class='panel panel-default'>
                <div class="panel-body">
                    @include('blog::forms.post',['categories'=>$categories])
                </div>
            </div>
        </div>
        <div class="col-md-3">
            @include('blog::pages.posts.top_right')
        </div>
    </div>
@endSection

@section('script')
    <script type="text/javascript">
        $("#summernote").summernote({
            height: 500
        });
        $('#blog_tags').select2();
    </script>
@endsection
