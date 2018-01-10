@extends(config('blog.layout'))
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('blog::categories.index')}}">
            <span class="glyphicon glyphicon-list"> categories</span>
        </a>
    </li>
    <li class="breadcrumb-item">Create</li>
@endsection
@section('content')
    <div class="row">
        <div class='col-md-12'>
            @include('blog::forms.category')
        </div>
    </div>
@endSection