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
                        <hr/>
                        <div class="media text-left">
                            @if(is_object($comment->user))
                                <img width="80px" src="{{$comment->user->getAvatarThumb()}}"
                                     class="mr-3 rounded-circle img-responsive"
                                     alt="{{$comment->user->username}}">
                            @else
                                <img width="80px" src="{{asset('frontend/img/default-avatar.png')}}"
                                     alt="User Removed"
                                     class="mr-3 border rounded-circle img-responsive"/>
                            @endif
                            <div class="media-body">
                                <h5 class="mt-0">{{$comment->user->username ??'Anonymous'}}</h5>
                                {{$comment->body}}
                            </div>
                            <div class="media-bottom">
                                {{$comment->created_at->diffForHumans()}}
                            </div>
                        </div>
                    @endforeach

                    @if(auth()->check())
                        <form action="{{route('blog::posts.comments.store',$record->slug)}}" method="post" class="mt-3">
                            {{csrf_field()}}
                            <div class="form-group">
                                <textarea class="form-control" name="body" placeholder="Say about this post"></textarea>
                            </div>
                            <div class="form-group text-right">
                                <input type="submit" value="Save" class="btn btn-primary">
                            </div>

                        </form>
                    @else
                        <a href="{{route('login')}}">Login </a>  to comment
                    @endif
                    @if($record->user)
                        <hr/>
                        <h3 class="h5 text-left">Author</h3>
                        <br/>
                        <div class="media">
                            <img width="120px" src="/images/blog_author_lady.png"
                                 class="mr-3 mr-3 rounded-circle img-responsive"
                                 alt="Emily Williams">
                            <div class="media-body">
                                <h5 class="mt-0 text-left">Emily Williams</h5>
                                Emily Williams is one of the members of the Content and Marketing team .
                                She is a marketing expert with a knack for digital marketing strategy, crafting and
                                implementation.
                            </div>
                        </div>
                    @endif
                </div>

                <aside class="col-md-3">

                    <h3 class="display-6">Table of Contents</h3>
                    {!! $record->table_of_content !!}
                    <div class="bg-light">

                        @foreach($record->tags as $tag)
                            <a class="btn badge badge-light p-1"
                               href="{{route('blog::frontend.blog.tags.index',$tag->slug)}}">{{$tag->name}}
                            </a>
                        @endforeach
                        <button class="btn badge badge-light">
                            <i class="fa fa-eye"></i> {{$record->total_view}}
                        </button>
                        <form action="{{route('blog::activities.store')}}" method="post" class="d-inline">
                            {{csrf_field()}}
                            <input type="hidden" name="activityable_type" value="{{get_class($record)}}">
                            <input type="hidden" name="activityable_id" value="{{$record->id }}">
                            <input type="hidden" name="type" value="like">
                            <button class="btn badge badge-light">
                                <i class="fa fa-thumbs-up"></i> {{$record->likes()->count()}}
                            </button>
                        </form>
                        <form action="{{route('blog::activities.store')}}" method="post" class="d-inline">
                            {{csrf_field()}}
                            <input type="hidden" name="activityable_type" value="{{get_class($record)}}">
                            <input type="hidden" name="activityable_id" value="{{$record->id }}">
                            <input type="hidden" name="type" value="favourite">
                            <button class="btn badge badge-light">
                                <i class="fa fa-star"></i> {{$record->favourites()->count()}}
                            </button>

                        </form>&nbsp;
                    </div>
                    <h3 class="display-6">Share</h3>
                    <ul class="share_this border-bottom border-reddish-orange mb-3 pb-3 list-inline">
                        <li class="list-inline-item">
                            <a href="javascript:void(0);" class="text-reddish-orange text-decoration-none">
                                <i class="fa fa-facebook fa-2x"></i>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="javascript:void(0);" class="text-reddish-orange text-decoration-none">
                                <i class="fa fa-twitter fa-2x"></i>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="javascript:void(0);" class="text-reddish-orange text-decoration-none">
                                <i class="fa fa-envelope fa-2x"></i>
                            </a>
                        </li>
                    </ul>
                </aside>
            </div>
            <hr/>
            <h3>Related Post</h3>
            <div class="row">
                @foreach($relatedPosts as $post)
                    <div class="col-md-4 col-sm-6">
                        <div class="card">
                            <img src="{{$post->getImageUrl()}}" class="card-img-top" alt="{{$post->title}}">
                            <div class="card-body">
                                <h3 class="card-title h6">
                                    <a href="{{route('blog::frontend.blog.posts.show',['category'=>$post->category->slug,'post'=>$post->slug])}}"> {{$post->title}}</a>
                                </h3>
                                {{$post->getSummary(120)}}
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
