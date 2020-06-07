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
