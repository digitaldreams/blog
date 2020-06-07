@extends('blog::layouts.emails.default')
@section('content')
    {{$post->title}} added by <b>{{$post->user->name}}</b> is  waiting for approval.
@endsection
