@extends(config('blog.layout.show'))
@section('breadcrumb')

    <li class="breadcrumb-item">
        <a href="{{route('blog::tags.index')}}">Tags</a>
    </li>
    <li class="breadcrumb-item">
        {{$record->name}}
    </li>
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