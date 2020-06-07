@extends(config('blog.layout'))
@section('breadcrumb')
    <li class="breadcrumb-item">
        activity types
    </li>
@endsection

@section('tools')
    <a href="{{route('blog::types.create')}}">
        <span class="fa fa-plus"></span> activity types
    </a>
@endsection

@section('content')
    @include('blog::tables.activity_type')
@endSection
