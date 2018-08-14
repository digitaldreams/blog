@extends('permit::layouts.app')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('blog::types.index')}}">activity types</a>
    </li>
    <li class="breadcrumb-item">
        Create
    </li>
@endsection
@section('content')
    <div class="row">
        <div class='col-md-12'>
            <div class='card bg-white'>
                <div class="card-body">
                    @include('blog::forms.activity_type')
                </div>
            </div>
        </div>
    </div>
@endSection