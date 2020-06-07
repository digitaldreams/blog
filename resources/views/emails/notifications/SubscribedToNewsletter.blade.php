@extends('layouts.emails.default')
@section('title')
@endsection
@section('content')
    {{$newsletter->name}} {{$newsletter->email}} subscribed to newsletter.
@endsection