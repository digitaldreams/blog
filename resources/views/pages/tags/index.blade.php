@extends(config('blog.layout'))
@section('breadcrumb')
    <li class="breadcrumb-item">tags</li>
@endsection
@section('header')
  <i class="fa fa-tags text-muted" style="font-size: 18px"></i> Tags
@endsection
@section('tools')
    @can('create',\Blog\Models\Tag::class)
        <a class="btn btn-secondary" href="{{route('blog::tags.create')}}"><span class="fa fa-plus"></span></a>
    @endcan
@endsection

@section('content')
    @if($records->count()>0)
        @include('blog::tables.tag')
    @else
        <div class="alert alert-warning">No tags found</div>
    @endif
@endSection

@section('script')
    @include('blog::pages.navigationscripts')
@endsection
