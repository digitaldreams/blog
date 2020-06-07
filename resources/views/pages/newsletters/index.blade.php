@extends(config('blog.layout'))

@section('breadcrumb')
    <li class="breadcrumb-item">
        newsletters
    </li>
@endsection
@section('header')
    <i class="fa fa-envelope-square"></i> Newsletter Subscribers
@endsection
@section('tools')

@endsection

@section('content')

    @include('blog::tables.newsletter')
@endSection
