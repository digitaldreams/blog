@extends(config('blog.layout'))
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('categories.index')}}">Categories</a>
    </li>
    <li class="breadcrumb-item">
        {{$record->title}}
    </li>
@endsection

@section('content')

    @if($posts->count()>0)
        <div class="row">
            @foreach($posts as $post)
                <div class="col-sm-6">
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