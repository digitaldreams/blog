@extends(config('blog.layout'))
@section('breadcrumb')
    <li class="breadcrumb-item">Categories</li>
@endsection
@section('tools')
    @can('create',\Blog\Models\Category::class)
        <a href="{{route('blog::categories.create')}}"><span class="fa fa-plus"></span></a>
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