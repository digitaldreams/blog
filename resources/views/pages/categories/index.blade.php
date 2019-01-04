@extends(config('blog.layout.show'))
@section('breadcrumb')
    <li class="breadcrumb-item">Categories</li>
@endsection
@section('header')
    <h3>
        <i class="fa fa-layer-group text-muted" style="font-size: 18px"></i> Categories
    </h3>

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