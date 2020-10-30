@extends('blog::layouts.frontend')
@section('css')
@endsection

@section('content')

    <section>
        <ul class="breadcrumb px-3 py-1" itemscope itemtype="http://schema.org/BreadcrumbList">
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
            @else
                <li class="breadcrumb-item active" itemprop="itemListElement" itemscope
                    itemtype="http://schema.org/ListItem">
                        <span itemprop="item">
                            <span itemprop="name">Posts</span>
                        </span>
                    <meta itemprop="position" content="3"/>
                </li>
            @endif
        </ul>
    </section>

    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="d-flex d-flex-row justify-content-between">
                <h2 class="h3 text-black  mb-4">
                    @if(isset($model) && is_object($model))
                        {{$model->title}} Posts
                    @else
                        Posts
                    @endif
                    @if($records->total()>0)
                        <small class="text-muted">(Showing {{$records->firstItem()}} to {{$records->lastItem()}} out
                            of {{$records->total()}})
                        </small>
                    @else
                        <span class="">No result found</span>
                    @endif
                </h2>

            </div>


        </div>
        <div class="col-md-6 col-sm-12">
            <form>
                <div class="input-group">
                    <div class="dropdown">
                        <a class="btn btn-outline-secondary dropdown-toggle" href="#" role="button"
                           id="dropdownMenuLink"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Topics
                        </a>

                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            @foreach($keywords as $keyword)
                                <li class="dropdown-item">
                                    <a class="btn btn-outline-secondary" href="?search={{$keyword['name']}}">
                                        {{$keyword['name']}} <span
                                            class="badge badge-secondary">{{$keyword['total']}}</span>
                                    </a>
                                </li>
                            @endforeach
                        </div>
                    </div>
                    <input type="search" value="{{request('search')}}" name="search" placeholder="search..."
                           id="search-area" list="keywords"
                           class="form-control">
                    <button type="submit" class="btn btn-secondary">Search</button>
                    <datalist id="keywords">
                        @if(isset($keywords))
                            @foreach($keywords as $keyword)
                                <option value="{{$keyword['name']??''}}">
                            @endforeach
                        @endif
                    </datalist>
                </div>

            </form>
        </div>
    </div>
    @foreach($records as $post)
        <div class="card mb-4">
            <div class="row no-gutters">
                <div class="col-md-3" style="max-height: 220px;overflow: hidden">
                    <a href="{{route('blog::frontend.blog.posts.show',['category'=>$post->category->slug,'post'=>$post->slug])}}">
                        <img src="{{$post->getImageUrl()}}" class="card-img"
                             style="object-fit:scale-down;object-position: center" alt="{{$post->title}}">
                    </a>
                </div>
                <div class="col-md-9">
                    <div class="card-body">
                        <div>
                            <div class="d-flex flex-row justify-content-between">
                                <h3 class="card-title h4">
                                    <a href="{{route('blog::frontend.blog.posts.show',['category'=>$post->category->slug,'post'=>$post->slug])}}">
                                        {{$post->title}}
                                    </a>
                                </h3>
                                @can('update',$post)
                                    @include('blog::includes.post_dropdown_menu',['record'=> $post])
                                @endcan
                            </div>
                        </div>

                        {{$post->getSummary(300)}}
                        <a href="{{route('blog::frontend.blog.posts.show',['category'=>$post->category->slug,'post'=>$post->slug])}}"
                           class="card-link">
                            Read more <i class="fa fa-chevron-right"></i>
                        </a>
                        <hr/>
                        <div class="text-right">
                            <form action="{{route('blog::activities.store')}}" method="post"
                                  class="d-inline">
                                {{csrf_field()}}
                                <input type="hidden" name="activityable_type" value="{{get_class($post)}}">
                                <input type="hidden" name="activityable_id" value="{{$post->id }}">
                                <input type="hidden" name="type" value="like">
                                <button title="likes" class="btn badge badge-light">
                                    <i class="fa fa-thumbs-up"></i> {{$post->likes()->count()}} Likes
                                </button>
                            </form>
                            <form action="{{route('blog::activities.store')}}" method="post"
                                  class="d-inline">
                                {{csrf_field()}}
                                <input type="hidden" name="activityable_type" value="{{get_class($post)}}">
                                <input type="hidden" name="activityable_id" value="{{$post->id }}">
                                <input type="hidden" name="type" value="favourite">
                                <button title="favourite" class="btn badge badge-light">
                                    <i class="fa fa-star"></i> {{$post->favourites()->count()}} Favourite

                                </button>
                            </form>&nbsp;
                            <button title="comments" class="btn badge badge-light"><i
                                    class="fa fa-comments"></i> {{$post->comments_count ??0}}</button>
                            <a href="{{route('blog::frontend.blog.categories.index',$post->category->slug)}}"
                               class="card-link">{{$post->category->title}}
                            </a>
                            @foreach($post->tags as $tag)
                                <a href="{{route('blog::frontend.blog.posts.index',['search'=>$tag->name])}}"> <span
                                        class="badge badge-light">{{$tag->name}}</span>
                                </a>
                            @endforeach

                        </div>
                    </div>
                </div>

            </div>
        </div>
    @endforeach
    <hr/>

    {!! $records->render() !!}

@endsection
@section('scripts')
@endsection
