@extends('layouts.emails.master')

@section('title')
    Weekly Newsletter
@endsection
@section('content')
    <h2>Your weekly update about posts</h2>
    <table>
        <tbody>
        @foreach($posts as $post)
            <tr>
                <td>
                    <h3>{{$post->title}}</h3>
                    <p>
                        {{$post->getSummary(150)}}
                        <a href="{{route('blog::frontend.blog.posts.show',['category'=>$post->category->slug,'post'=>$post->slug])}}">
                            Read More
                        </a>
                    </p>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection