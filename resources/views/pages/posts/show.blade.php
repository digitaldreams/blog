@extends(config('blog.layout'))
@section('breadcrumb')

    <li class="breadcrumb-item">
        <a href="{{route('blog::posts.index')}}">Posts</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{route('blog::categories.show',$record->category->slug)}}">{{$record->category->title}}</a>
    </li>
    <li class="breadcrumb-item">
        {{$record->title}}
    </li>
@endsection
@section('header')
    {{$record->title}}
@endsection
@section('tools')
    <div class="btn-group btn-group-sm">
        <a href="{{route('blog::frontend.blog.posts.show',['category'=>$record->category->slug,'post'=>$record->slug])}}"
           target="_blank"
           class="btn btn-secondary"> <i class="fa fa-eye"></i> Preview</a>
        @if($record->status==\Blog\Models\Post::STATUS_PENDING)
            <form action="{{route('blog::posts.status',[
            'post'=>$record->slug,
            'status'=>\Blog\Models\Post::STATUS_PUBLISHED
            ])}}" method="post">
                {{csrf_field()}}
                <button type="submit" class="btn btn-outline-primary"><i class="fa fa-check-circle-o"></i> Publish
                </button>
            </form>

            <form action="{{route('blog::posts.status',[
            'post'=>$record->slug,
            'status'=>\Blog\Models\Post::STATUS_REJECTED
            ])}}" method="post">
                {{csrf_field()}}
                <button type="submit" class="btn btn-danger"><i class="fa fa-times"></i> Reject</button>
            </form>
        @else
            <span class="btn btn-secondary">{{$record->status}}</span>
            <span class="btn btn-secondary"><i class="fa fa-thumbs-up"></i> {{$record->likes()->count()}}</span>
            <span class="btn btn-secondary"><i
                    class="fa fa-star text-warning"></i> {{$record->favourites()->count()}}</span>
            <span class="btn btn-secondary"><i class="fa fa-comments"></i> {{$record->comments()->count()}}</span>
        @endif

        <a href="{{route('blog::posts.edit',$record->slug)}}"
           class="btn btn-secondary"> <i class="fa fa-pencil-alt"></i> Edit</a>
        @include('blog::forms.destroy',['route'=>route('blog::posts.destroy',$record->slug)])
    </div>
@endsection
@section('content')

    <div class="description my-4">
        {!! $record->body !!}
    </div>

@endSection
