@extends('blog::layouts.frontend')
@section('css')
@endsection

@section('content')
    <section class="breadcum-sec">
        <div class="container">
            <ul class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
                <li class="breadcrumb-item" itemprop="itemListElement" itemscope
                    itemtype="http://schema.org/ListItem">
                    <a itemprop="item" href="{{url('/')}}">
                        <span itemprop="name">Home</span>
                    </a>
                    <meta itemprop="position" content="1"/>
                </li>
                <li class="breadcrumb-item" itemprop="itemListElement" itemscope
                    itemtype="http://schema.org/ListItem">
                    <span itemprop="item">
                        <span itemprop="name">Blog</span>
                    </span>
                    <meta itemprop="position" content="2"/>
                </li>
            </ul>
        </div>
    </section>
    <section class="container">
        <div class="m-md-3 text-center">
            <p class="text-muted display-5">Take advantage of 21st Century SEO trends, learn marketing tips, discover
                how to leverage the power of customer reviews and most importantly protect your online brand reputation.
            </p>
            <form>
                <input type="search" value="{{request('search')}}" placeholder="search..."
                       class="form-control form-control-lg" id="search-area">
            </form>
        </div>

        <hr/>
        <h2 class="h4">Featured Posts</h2>
        <div class="row">
            <div class="col-md-6 col-sm-6">
                <div class="card">
                    @if($leadPost->image)
                        <img src="{{$leadPost->image->getUrl()}}" class="card-img-top" alt="{{$leadPost->title}}">
                    @else
                        <img src="{{$leadPost->getImageUrl()}}" class="card-img-top" alt="{{$leadPost->title}}">
                    @endif

                    <div class="card-body">
                        <h3 class="card-title h3">
                            <a href="{{route('blog::frontend.blog.posts.show',['category'=>$leadPost->category->slug,'post'=>$leadPost->slug])}}"> {{$leadPost->title}}</a>
                        </h3>
                        <p class="mb-0">{{$leadPost->getSummary(200)}}</p>
                        <hr/>
                        <a href="{{route('blog::frontend.blog.categories.index',$leadPost->category->slug)}}"
                           class="card-link">{{$leadPost->category->title}}</a>
                        <a href="{{route('blog::frontend.blog.posts.show',['category'=>$leadPost->category->slug,'post'=>$leadPost->slug])}}"
                           class="card-link">Read more <i
                                class="fa fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6">
                <div class="row">
                    @foreach($featuredPosts as $fpost)
                        <div class="col-sm-12 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title h5">
                                        <a href="{{route('blog::frontend.blog.posts.show',['category'=>$fpost->category->slug,'post'=>$fpost->slug])}}"> {{$fpost->title}}</a>
                                    </h3>
                                    <p class="mb-0">{{$fpost->getSummary(150)}}
                                        <a href="{{route('blog::frontend.blog.posts.show',['category'=>$fpost->category->slug,'post'=>$fpost->slug])}}"
                                           class="card-link">
                                            Read more <i class="fa fa-chevron-right"></i>
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
        <hr/>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active text-primary font-weight-bold" href="#">Latest Posts</a>
            </li>
            @foreach($categories as $category)
                <li class="nav-item">
                    <a class="nav-link"
                       href="{{route('blog::frontend.blog.categories.index',$category->slug)}}">
                        {{$category->title}}
                        <small class="badge badge-light badge-pill">{{$category->total}}</small>
                    </a>
                </li>
            @endforeach
        </ul>
        <br/>
        @foreach($latest as $post)
            <div class="card mb-3">
                <div class="row no-gutters">
                    <div class="col-md-2 text-center" style="max-height: 230px;overflow: hidden">
                        <img src="{{$post->getImageUrl()}}" style="object-fit: cover;object-position: center" class="card-img" alt="{{$post->title}}">
                    </div>
                    <div class="col-md-10">
                        <div class="card-body">
                            <h3 class="card-title h5">
                                <a href="{{route('blog::frontend.blog.posts.show',['category'=>$post->category->slug,'post'=>$post->slug])}}"> {{$post->title}}</a>
                            </h3>
                            <p class="mb-0">{{$post->getSummary(180)}}
                                <a href="{{route('blog::frontend.blog.posts.show',['category'=>$post->category->slug,'post'=>$post->slug])}}"
                                   class="card-link">
                                    Read more <i class="fa fa-chevron-right"></i>
                                </a>
                            </p>

                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <hr/>
        <div class="row bg-light align-items-center">
            <div class="col-sm-6 text-center p-5">
                <h3 class="text-brand">Join the Newsletter</h3>
                <p class="lead  text-muted">
                    Sign up to our newsletter to stay up to date with the latest on how to protect your online brand
                    reputation & amazing growth tools
                </p>
                <form action="{{route('blog::frontend.blog.newsletters.subscribe')}}" method="post">
                    {{csrf_field()}}
                    <div class="form-group py-3">
                        <div class="input-group">
                            <input type="text" name="name" value="{{old('name')}}"
                                   placeholder="Name" required class="form-control">
                            <input type="email" name="email" value="{{old('email')}}"
                                   placeholder="Email Address" required
                                   class="form-control {{$errors->has('email')?'is-invalid':''}}">
                            <div class="input-group-addon">
                                <input type="submit" value="Subscribe" class="btn btn-primary">
                            </div>
                        </div>
                        @if($errors->has('email'))
                            <span class="help-block">{{$errors->first('email')}}</span>
                        @endif
                    </div>
                </form>
            </div>
            <div class="col-sm-6">
                <div class="p-3 ml-5">
                    <picture>
                        <source srcset="/images/video-tutorial.jpg" type="image/jpeg">
                        <source srcset="/images/video-tutorial.jpg" type="image/jpg">
                        <img src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs="
                             data-src="/frontend/images/Group-35.jpg" class="img-fluid cursor-pointer"
                             data-toggle="modal" data-target="#myModal">
                    </picture>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 p-5 text-center">
                <h3>Our Popular Topics</h3>
                @foreach($tags as $tag)
                    <a class="btn btn-light" href="{{route('blog::frontend.blog.tags.index',$tag->slug)}}">
                        {{$tag->name}} <span class="badge badge-pill badge-secondary">{{$tag->total}}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <div class="modal fade video-modal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <video controls="" id="video1" width="598" preload="none"
                           style="width: 100%; height: auto; margin:0 auto; frameborder:0;">
                        <source src="/frontend/images/business_sub.mp4" type="video/mp4">
                    </video>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    @include('blog::pages.posts.frontend.search')
@endsection
