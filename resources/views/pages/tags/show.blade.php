@extends(config('blog.layout'))
@section('breadcrumb')

    <li class="breadcrumb-item">
        <a href="{{route('blog::tags.index')}}">Tags</a>
    </li>
    <li class="breadcrumb-item">
        {{$record->name}}
    </li>
@endsection
@section('header')
<i class="fa fa-tag text-muted" style="font-size: 18px"></i> {{$record->name}}
@endsection
@section('tools')
    @can('create',\Blog\Models\Tag::class)
        <a class="btn btn-secondary" href="{{route('blog::tags.create')}}"><i class="fa fa-plus"></i> New Tag</a>
    @endcan
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
