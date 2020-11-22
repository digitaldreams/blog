@extends('blog::layouts.frontend')
@section('meta')
    <script type="application/ld+json">
        {!! json_encode($record->breadcrumbList()) !!}
    </script>
@endsection

@section('content')
    <section class="breadcum-sec my-3">
        <div class="container">
            <ul class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{url('/')}}">
                        <span>Home</span>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{route('blog::posts.home')}}">
                        <span>Blog</span>
                    </a>
                </li>
                @if(is_object($record->category->parentCategory))
                    <li class="breadcrumb-item">
                        <a href="{{route('blog::frontend.blog.categories.index',['category'=>$record->category->parentCategory->slug])}}">
                            <span>{{$record->category->parentCategory->title ??''}}</span>
                        </a>
                    </li>
                @endif
                @if(is_object($record->category))
                    <li class="breadcrumb-item">
                        <a href="{{route('blog::frontend.blog.categories.index',['category'=>$record->category->slug])}}">
                            {{$record->category->title}}
                        </a>
                    </li>
                @endif

                <li class="breadcrumb-item">
                           <span>
                               {{$record->title}}
                           </span>
                    @can('update',$record)
                        <a href="{{route('blog::posts.edit',$record->slug)}}"><i class="fa fa-pencil"></i>
                        </a>
                    @endcan
                </li>
            </ul>
        </div>
    </section>
    <section class="post_detail py-md-3">
        <div class="container">
            <div class="row mt-4">
                <div class="col-md-9 mb-md-0 mb-3">
                    <h1>{{$record->title}}</h1>

                    <div class="description my-4">
                        {!! $record->body !!}

                    </div>


                    @foreach($record->comments as $comment)
                        <div class="mb-4">
                            <div class="d-flex flex-row justify-content-start">
                                <div class="" style="max-height: 220px;overflow: hidden">
                                    @if(is_object($comment->user))
                                        <img width="80px" src="{{$comment->user->getAvatarThumb()}}"
                                             class="mr-3 rounded-circle img-responsive"
                                             alt="{{$comment->user->username}}">
                                    @else
                                        <img width="80px" src="{{asset('frontend/img/default-avatar.png')}}"
                                             alt="User Removed"
                                             class="mr-3 border rounded-circle img-responsive"/>
                                    @endif
                                </div>
                                <div class="">
                                    <figure>
                                        <blockquote class="blockquote">
                                            <p> {{$comment->body}}</p>
                                        </blockquote>
                                        <figcaption class="blockquote-footer">
                                            {{$comment->created_at->diffForHumans()}} by <cite
                                                title="Source Title">{{$comment->user->username ??'Anonymous'}}</cite>
                                        </figcaption>
                                    </figure>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if(auth()->check())
                        <form action="{{route('blog::posts.comments.store',$record->slug)}}" method="post"
                              class="mt-3">
                            {{csrf_field()}}
                            <div class="mb-3">
                                        <textarea class="form-control" name="body"
                                                  placeholder="Say about this post"></textarea>
                            </div>
                            <div class="form-group text-right">
                                <input type="submit" value="Save" class="btn btn-primary">
                            </div>

                        </form>
                    @else
                        <a href="{{route('login')}}">Login </a>  to comment
                    @endif
                    @if($record->user)
                        <h3 class="h5 text-left">Author</h3>
                        <br/>
                        <div class="d-flex flex-row justify-content-start">
                            <div class="text-center">
                                <img width="120px" src="{{$record->user->getAvatarThumb()}}"
                                     class="mr-3 mr-3 rounded-circle img-responsive"
                                     alt="{{$record->user->name??''}}">
                                <h5 class="mt-0 text-left">{{$record->user->name??''}}</h5>
                            </div>
                            <div class="">

                                {{$record->user->about ??""}}
                            </div>
                        </div>
                    @endif
                </div>

                <aside class="col-md-3">

                    <h3 class="display-6">Table of Contents</h3>
                    {!! $record->table_of_content !!}
                    <div class="bg-light">

                        @foreach($record->tags as $tag)
                            <a class="badge bg-light p-1"
                               href="{{route('blog::frontend.blog.posts.index',['search'=>$tag->name])}}">{{$tag->name}}
                            </a>
                        @endforeach
                        <button class="badge bg-secondary">
                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-eye-fill"
                                 fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                                <path fill-rule="evenodd"
                                      d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                            </svg> {{$record->total_view}}
                        </button>
                        <form action="{{route('blog::activities.store')}}" method="post" class="d-inline">
                            {{csrf_field()}}
                            <input type="hidden" name="activityable_type" value="{{get_class($record)}}">
                            <input type="hidden" name="activityable_id" value="{{$record->id }}">
                            <input type="hidden" name="type" value="like">
                            <button class="badge bg-secondary">
                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-hand-thumbs-up"
                                     fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                          d="M6.956 1.745C7.021.81 7.908.087 8.864.325l.261.066c.463.116.874.456 1.012.965.22.816.533 2.511.062 4.51a9.84 9.84 0 0 1 .443-.051c.713-.065 1.669-.072 2.516.21.518.173.994.681 1.2 1.273.184.532.16 1.162-.234 1.733.058.119.103.242.138.363.077.27.113.567.113.856 0 .289-.036.586-.113.856-.039.135-.09.273-.16.404.169.387.107.819-.003 1.148a3.163 3.163 0 0 1-.488.901c.054.152.076.312.076.465 0 .305-.089.625-.253.912C13.1 15.522 12.437 16 11.5 16v-1c.563 0 .901-.272 1.066-.56a.865.865 0 0 0 .121-.416c0-.12-.035-.165-.04-.17l-.354-.354.353-.354c.202-.201.407-.511.505-.804.104-.312.043-.441-.005-.488l-.353-.354.353-.354c.043-.042.105-.14.154-.315.048-.167.075-.37.075-.581 0-.211-.027-.414-.075-.581-.05-.174-.111-.273-.154-.315L12.793 9l.353-.354c.353-.352.373-.713.267-1.02-.122-.35-.396-.593-.571-.652-.653-.217-1.447-.224-2.11-.164a8.907 8.907 0 0 0-1.094.171l-.014.003-.003.001a.5.5 0 0 1-.595-.643 8.34 8.34 0 0 0 .145-4.726c-.03-.111-.128-.215-.288-.255l-.262-.065c-.306-.077-.642.156-.667.518-.075 1.082-.239 2.15-.482 2.85-.174.502-.603 1.268-1.238 1.977-.637.712-1.519 1.41-2.614 1.708-.394.108-.62.396-.62.65v4.002c0 .26.22.515.553.55 1.293.137 1.936.53 2.491.868l.04.025c.27.164.495.296.776.393.277.095.63.163 1.14.163h3.5v1H8c-.605 0-1.07-.081-1.466-.218a4.82 4.82 0 0 1-.97-.484l-.048-.03c-.504-.307-.999-.609-2.068-.722C2.682 14.464 2 13.846 2 13V9c0-.85.685-1.432 1.357-1.615.849-.232 1.574-.787 2.132-1.41.56-.627.914-1.28 1.039-1.639.199-.575.356-1.539.428-2.59z"/>
                                </svg>
                                {{$record->likes()->count()}}
                            </button>
                        </form>
                        <form action="{{route('blog::activities.store')}}" method="post" class="d-inline">
                            {{csrf_field()}}
                            <input type="hidden" name="activityable_type" value="{{get_class($record)}}">
                            <input type="hidden" name="activityable_id" value="{{$record->id }}">
                            <input type="hidden" name="type" value="favourite">
                            <button class="badge bg-secondary">
                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-star-fill"
                                     fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.283.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                </svg> {{$record->favourites()->count()}}
                            </button>

                        </form>&nbsp;
                    </div>
                    <ul class="share_this border-bottom border-reddish-orange mb-3 pb-3 list-inline">
                        <li class="list-inline-item">
                            <a href="javascript:void(0);" class="text-reddish-orange text-decoration-none">
                                <i class="fa fa-facebook"></i>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="javascript:void(0);" class="text-reddish-orange text-decoration-none">
                                <i class="fa fa-twitter "></i>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="javascript:void(0);" class="text-reddish-orange text-decoration-none">
                                <i class="fa fa-envelope"></i>
                            </a>
                        </li>
                    </ul>
                </aside>
            </div>
            <hr/>
            <h3>Related Post</h3>
            <div class="row">
                @foreach($relatedPosts as $post)
                    <div class="col-md-6 col-sm-12">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h3 class="card-title h6">
                                    <a href="{{route('blog::frontend.blog.posts.show',['category'=>$post->category->slug,'post'=>$post->slug])}}"> {{$post->title}}</a>
                                </h3>
                                {{$post->getSummary(150)}}
                                <hr/>
                                <a href="{{route('blog::frontend.blog.categories.index',$post->category->slug)}}"
                                   class="card-link">{{$post->category->title}}</a>
                                <a href="{{route('blog::frontend.blog.posts.show',['category'=>$post->category->slug,'post'=>$post->slug])}}"
                                   class="card-link">
                                    Read more <i class="fa fa-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

@endSection
