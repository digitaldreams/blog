@extends('layouts.emails.default')
@section('title')
@endsection
@section('content')
    {{$post->title}} is  {{$post->staus}}.
@endsection