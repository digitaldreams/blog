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
                    <a itemprop="item" href="{{route('blog::posts.home')}}">
                        <span itemprop="name">Blog</span>
                    </a>
                    <meta itemprop="position" content="2"/>
                </li>
                @if(isset($model))
                    <li class="breadcrumb-item" itemprop="itemListElement" itemscope
                        itemtype="http://schema.org/ListItem">
                        <span itemprop="item">
                            <span itemprop="name">{{$model->title ??''}}</span>
                        </span>
                        <meta itemprop="position" content="3"/>
                    </li>
                @endif
            </ul>
        </div>
    </section>

    <section class="">
        <div class="container">

            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <h1 class="h3 text-black  mb-4">
                        @if(isset($model) && is_object($model))
                            {{$model->title}} Posts
                        @else
                            Posts
                        @endif
                    </h1>
                </div>
                <div class="col-md-6 col-sm-12">
                    <form>
                        <input type="search" value="{{request('search')}}" placeholder="search..." id="search-area"
                               class="form-control form-control-lg">
                    </form>
                </div>
            </div>
            @foreach($records->chunk(3) as $posts)
                <div class="row my-3">
                    @foreach($posts as $post)
                        <div class="col-md-4 col-sm-6">
                            <div class="card h-100">
                                <img src="{{$post->getImageUrl()}}" class="card-img-top" alt="{{$post->title}}">
                                <div class="card-body">
                                    <h3 class="card-title h6">
                                        <a href="{{route('blog::frontend.blog.posts.show',['category'=>$post->category->slug,'post'=>$post->slug])}}"> {{$post->title}}</a>
                                    </h3>
                                    {{$post->getSummary(120)}}
                                    <a href="{{route('blog::frontend.blog.posts.show',['category'=>$post->category->slug,'post'=>$post->slug])}}"
                                       class="card-link">
                                        Read more <i class="fa fa-chevron-right"></i>
                                    </a>
                                    <hr/>
                                </div>
                                <div class="card-footer">
                                    <div>
                                        <a href="{{route('blog::frontend.blog.categories.index',$post->category->slug)}}"
                                           class="card-link">{{$post->category->title}}</a>
                                        <form action="{{route('blog::activities.store')}}" method="post"
                                              class="d-inline">
                                            {{csrf_field()}}
                                            <input type="hidden" name="activityable_type" value="{{get_class($post)}}">
                                            <input type="hidden" name="activityable_id" value="{{$post->id }}">
                                            <input type="hidden" name="type" value="like">
                                            <button class="btn badge badge-light">
                                                <i class="fa fa-thumbs-up"></i> {{$post->likes()->count()}}
                                            </button>
                                        </form>
                                        <form action="{{route('blog::activities.store')}}" method="post"
                                              class="d-inline">
                                            {{csrf_field()}}
                                            <input type="hidden" name="activityable_type" value="{{get_class($post)}}">
                                            <input type="hidden" name="activityable_id" value="{{$post->id }}">
                                            <input type="hidden" name="type" value="favourite">
                                            <button class="btn badge badge-light">
                                                <i class="fa fa-star"></i> {{$post->favourites()->count()}}
                                            </button>
                                        </form>&nbsp;
                                        <button class="btn badge badge-light"><i
                                                    class="fa fa-comments"></i> {{$post->comments_count ??0}}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <hr/>
            @endforeach

            {!! $records->render() !!}

        </div>
    </section>
@endsection
@section('scripts')
    @include('blog::pages.posts.frontend.search')
@endsection
