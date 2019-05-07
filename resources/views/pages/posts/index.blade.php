@extends(config('blog.layout.show'))
@section('breadcrumb')
    <li class="breadcrumb-item">
        Posts
    </li>
@endsection
@section('header')
<h3> Posts</h3>
@endsection
@section('tools')
    @can('create',\Blog\Models\Post::class)
      <button class="btn btn-primary" onclick="location.href='{{route('blog::posts.create')}}'" type="button">
        <span class="fa fa-plus"></span> create post
      </button>
    @endcan
@endsection
@section('content')

    @if($records->count()>0)
        <div class="row blog-post" id="blog-post">
            @foreach($records as $record)
                <div class="card-group col-md-4 col-sm-6">
                    @include('blog::cards.post')
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-warning">No posts found</div>
    @endif

    <div class='row'>
        <div class="col-sm-12">
            {{{$records->render()}}}
        </div>
    </div>
@endSection