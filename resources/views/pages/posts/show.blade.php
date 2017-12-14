@extends(config('blog.layout'))
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('posts.index')}}">Posts</a>
    </li>
    <li class="breadcrumb-item">
        {{$record->title}}
    </li>
@endsection
@section('tools')
    <a href="{{route('posts.edit',$record->slug)}}">
        <span class="fa fa-pencil"></span>
    </a>
    &nbsp;&nbsp;
    <a href="{{route('posts.create')}}">
        <span class="fa fa-plus"></span>
    </a>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <img src="{{$record->getImageUrl()}}" class="img-responsive img-rounded">
            <h1>{{$record->title}}</h1>
            {!! $record->content !!}

            @foreach($record->comments as $comment)
                <div class="card mb-3">
                    <blockquote class="card-body quote">
                        <p>
                            <img align="left" class="img-responsive img-thumbnail mr-2" style="max-width: 60px"
                                 src="{{$comment->user->getAvatarThumb()}}"> {{$comment->body}}
                        </p>
                        <footer class="text-right">
                            <small class="text-muted">
                                {{$comment->user->getFullName()}} in <cite
                                        title="Source Title">{{$comment->created_at->diffForHumans()}}</cite>
                            </small>
                        </footer>
                    </blockquote>
                </div>
            @endforeach
            @if(auth()->check())
                <form action="{{route('posts.comments.store',$record->slug)}}" method="post">
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
    </div>
@endSection