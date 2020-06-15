@extends(config('blog.layout'))
@section('breadcrumb')
    <li class="breadcrumb-item">Categories</li>
@endsection
@section('header')
        <i class="fa fa-layer-group text-muted" style="font-size: 18px"></i> Categories

@endsection
@section('tools')
    @can('create',\Blog\Models\Category::class)
        <a class="btn btn-secondary" href="{{route('blog::categories.create')}}"><span class="fa fa-plus"></span></a>
    @endcan
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <form>
                <div class="input-group mb-3">
                    <input type="search" name="search" value="{{request('search')}}" class="form-control" placeholder="Search Category"
                           aria-label="Search category" aria-describedby="button-addon2">
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
        @include('blog::tables.category')
    @else
        <div class="alert alert-warning">No category found</div>
    @endif
    {!! $records->render() !!}
@endSection


@section('script')
    @include('blog::pages.navigationscripts')
@endsection
