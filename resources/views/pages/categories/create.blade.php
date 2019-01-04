@extends(config('blog.layout.show'))
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('blog::categories.index')}}">
            categories
        </a>
    </li>
    <li class="breadcrumb-item">Create</li>
@endsection
@section('header')
    <h3><i class="fa fa-plus text-muted" style="font-size: 18px"></i> Create New Category</h3>
@endsection
@section('content')
    <div class="row">
        <div class='col-md-12'>
            @include('blog::forms.category')
        </div>
    </div>
@endSection