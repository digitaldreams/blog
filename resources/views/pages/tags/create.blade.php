@extends(config('blog.layout.show'))
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('blog::tags.index')}}">
            <span class="glyphicon glyphicon-list"> tags</span>
        </a>
    </li>
    <li class="breadcrumb-item">Create</li>
@endsection
@section('content')
    <div class="row">
        <div class='col-md-12'>
            @include('blog::forms.tag')
        </div>
    </div>
@endSection