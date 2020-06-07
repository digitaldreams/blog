@extends(config('blog.layout'))
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('blog::categories.index')}}">
            categories
        </a>
    </li>
    <li class="breadcrumb-item">Create</li>
@endsection
@section('header')
  <i class="fa fa-plus text-muted" style="font-size: 18px"></i> Create New Category
@endsection
@section('content')
    <div class="row">
        <div class='col-md-12'>
            @include('blog::forms.category')
        </div>
    </div>
@endSection
