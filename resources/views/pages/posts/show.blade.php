@extends(config('blog.layout'))
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('blog::categories.show',$record->category->slug)}}">{{$record->category->title}}</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{route('blog::posts.index')}}">Posts</a>
    </li>
    <li class="breadcrumb-item">
        {{$record->title}}
    </li>
@endsection
@section('tools')
    @if(auth()->check())
        &nbsp;
        <a href="{{route('blog::posts.edit',$record->slug)}}">
            <span class="fa fa-pencil"></span>
        </a>
        &nbsp;&nbsp;
        @include('blog::forms.destroy',['route'=>route('blog::posts.destroy',$record->slug)])

        <a href="{{route('blog::posts.create')}}">
            <span class="fa fa-plus"></span>
        </a>

    @endif
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-9">
            <img src="{{$record->getImageUrl()}}" class="img-responsive img-rounded">
            <h1>{{$record->title}}</h1>
            {!! $record->content !!}

            @foreach($record->comments as $comment)
                <div class="card mb-3">
                    <blockquote class="p-1 quote">
                        <p>
                            <img align="left" class="img-responsive img-thumbnail mr-2" style="max-width: 60px"
                                 src="{{$comment->user->getAvatarThumb()}}"> {{$comment->body}}
                        </p>
                        <footer class="blockquote-footer text-right">
                            <small class="text-muted">
                                {{$comment->user->getFullName()}} in
                                <cite title="Source Title">{{$comment->created_at->diffForHumans()}}</cite>
                            </small>
                        </footer>
                    </blockquote>
                </div>
            @endforeach
            @if(auth()->check())
                <form action="{{route('blog::posts.comments.store',$record->slug)}}" method="post">
                    {{csrf_field()}}
                    <div class="form-group">
                        <textarea class="form-control" name="body" placeholder="Say about this post"></textarea>
                    </div>
                    <div class="form-group text-right">
                        <input type="submit" value="Save" class="btn btn-primary">
                    </div>

                </form>
            @else
                <a href="{{route('login')}}">Login in</a>  to comments
            @endif
        </div>
        <aside class="col-sm-3">
            <h3 class="h6">Related posts</h3>
            @foreach($relatedPosts as $post)
                @include('blog::cards.post-short',['record'=>$post])
            @endforeach
        </aside>
    </div>
@endSection