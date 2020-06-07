@extends(config('blog.layout'))
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('blog::newsletters.index')}}">newsletters</a>
    </li>
    <li class="breadcrumb-item">
        {{$record->email}}
    </li>

@endsection
@section('header')
        <i class="fa fa-envelope-square text-muted" style="font-size: 18px;"></i> {{$record->email}}
@endsection
@section('tools')
    <div class="btn-group">


    </div>
@endsection

@section('content')


@endSection
