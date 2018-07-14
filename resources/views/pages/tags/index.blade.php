@extends(config('blog.layout.show'))
@section('breadcrumb')
    <li class="breadcrumb-item">tags</li>
@endsection
@section('tools')
    @can('create',\Blog\Models\Tag::class)
        <a href="{{route('blog::tags.create')}}"><span class="fa fa-plus"></span></a>
    @endcan
@endsection

@section('content')
    @if($records->count()>0)
        @include('blog::tables.tag')
    @else
        <div class="alert alert-warning">No tags found</div>
    @endif
@endSection