@extends(config('blog.layout'))
@section('breadcrumb')
    <li class="breadcrumb-item">
        Posts
    </li>
@endsection
@section('header')
    Posts
@endsection
@section('tools')
    <div class="btn-group">
        @can('create',\Blog\Models\Post::class)

            <a class="btn btn-secondary" href="{{route('blog::posts.home')}}">
                <span class="fa fa-home"></span> <span class="d-none">Home</span>
            </a>

            <a class="btn btn-secondary" href="{{route('blog::posts.create')}}">
                <span class="fa fa-plus"></span> <span class="d-none">Create New</span>
            </a>
        @endcan
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-6">
            <form>
                <div class="input-group mb-3">
                    <input type="search" name="search" value="{{request('search')}}" class="form-control" placeholder="Search Post"
                           aria-label="Search Post title" aria-describedby="button-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Search</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-6">
            {!! $records->render() !!}
        </div>
    </div>
    @if($records->count()>0)


        <div class="row">
            @foreach($records as $record)
                <div class="card-group col-md-3 col-sm-4 col-12">
                    @include('blog::cards.post')
                </div>
            @endforeach
        </div>
        <div class='row'>
            <div class="col-sm-12">
                {!! $records->render() !!}
            </div>
        </div>
    @else
        <div class="alert alert-warning">No posts found</div>
    @endif


@endSection


@section('script')
    @include('blog::pages.navigationscripts')
@endsection
