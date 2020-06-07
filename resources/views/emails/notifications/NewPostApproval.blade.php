@extends('blog::layouts.emails.default')
@section('content')
    {{$post->title}} added by <b>{{$post->user->memberName}}</b> is  waiting for approval.
@endsection
