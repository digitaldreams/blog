@extends(config('blog.layout'))
@section('breadcrumb')
    <li class="breadcrumb-item">
        Posts
    </li>
@endsection
@section('tools')
    <a href="{{route('blog::posts.create')}}"><span class="fa fa-plus"></span></a>
@endsection
@section('content')

    @if($records->count()>0)
        <div class="row">
            @foreach($records as $record)
                <div class="col-sm-4">
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