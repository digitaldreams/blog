@extends(config('blog.layout.show'))
@section('styles')
  <link rel="stylesheet" href="{{ asset('css/tags.css') }}"> 
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item">tags</li>
@endsection
@section('header')
    <h3><i class="fa fa-tags text-muted" style="font-size: 18px"></i> Tags </h3>
@endsection
@section('tools')
    @can('create',\Blog\Models\Tag::class)
        <a class="btn btn-secondary" title="" data-toggle="tooltip" data-original-title="Create New Tag" href="{{route('blog::tags.create')}}"><span class="fa fa-plus"></span></a>
    @endcan
@endsection

@section('content')
    @if($records->count()>0)
        @include('blog::tables.tag')
    @else
        <div class="alert alert-warning">No tags found</div>
    @endif
@endSection
