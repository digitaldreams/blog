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

                        @if($post->image)
                            {!! $post->image->renderThumbnails('card-img','object-fit: cover;object-position: center') !!}
                        @else
                            <img src="{{config('blog.defaultPhoto')}}" alt="{{$post->title}}" class="card-img"
                                 style="object-fit: cover;object-position: center">
                        @endif
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
                            Read more <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-chevron-right" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
                            </svg>
                        </a>
                        <hr/>
                        <div class="text-right">
                            <form action="{{route('blog::activities.store')}}" method="post"
                                  class="d-inline">
                                {{csrf_field()}}
                                <input type="hidden" name="activityable_type" value="{{get_class($post)}}">
                                <input type="hidden" name="activityable_id" value="{{$post->id }}">
                                <input type="hidden" name="type" value="like">
                                <button title="likes" class="btn btn-light">
                                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-hand-thumbs-up" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M6.956 1.745C7.021.81 7.908.087 8.864.325l.261.066c.463.116.874.456 1.012.965.22.816.533 2.511.062 4.51a9.84 9.84 0 0 1 .443-.051c.713-.065 1.669-.072 2.516.21.518.173.994.681 1.2 1.273.184.532.16 1.162-.234 1.733.058.119.103.242.138.363.077.27.113.567.113.856 0 .289-.036.586-.113.856-.039.135-.09.273-.16.404.169.387.107.819-.003 1.148a3.163 3.163 0 0 1-.488.901c.054.152.076.312.076.465 0 .305-.089.625-.253.912C13.1 15.522 12.437 16 11.5 16v-1c.563 0 .901-.272 1.066-.56a.865.865 0 0 0 .121-.416c0-.12-.035-.165-.04-.17l-.354-.354.353-.354c.202-.201.407-.511.505-.804.104-.312.043-.441-.005-.488l-.353-.354.353-.354c.043-.042.105-.14.154-.315.048-.167.075-.37.075-.581 0-.211-.027-.414-.075-.581-.05-.174-.111-.273-.154-.315L12.793 9l.353-.354c.353-.352.373-.713.267-1.02-.122-.35-.396-.593-.571-.652-.653-.217-1.447-.224-2.11-.164a8.907 8.907 0 0 0-1.094.171l-.014.003-.003.001a.5.5 0 0 1-.595-.643 8.34 8.34 0 0 0 .145-4.726c-.03-.111-.128-.215-.288-.255l-.262-.065c-.306-.077-.642.156-.667.518-.075 1.082-.239 2.15-.482 2.85-.174.502-.603 1.268-1.238 1.977-.637.712-1.519 1.41-2.614 1.708-.394.108-.62.396-.62.65v4.002c0 .26.22.515.553.55 1.293.137 1.936.53 2.491.868l.04.025c.27.164.495.296.776.393.277.095.63.163 1.14.163h3.5v1H8c-.605 0-1.07-.081-1.466-.218a4.82 4.82 0 0 1-.97-.484l-.048-.03c-.504-.307-.999-.609-2.068-.722C2.682 14.464 2 13.846 2 13V9c0-.85.685-1.432 1.357-1.615.849-.232 1.574-.787 2.132-1.41.56-.627.914-1.28 1.039-1.639.199-.575.356-1.539.428-2.59z"/>
                                    </svg> {{$post->likes()->count()}} Likes
                                </button>
                            </form>
                            <form action="{{route('blog::activities.store')}}" method="post"
                                  class="d-inline">
                                {{csrf_field()}}
                                <input type="hidden" name="activityable_type" value="{{get_class($post)}}">
                                <input type="hidden" name="activityable_id" value="{{$post->id }}">
                                <input type="hidden" name="type" value="favourite">
                                <button title="favourite" class="btn btn-light">
                                    <svg  width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-heart-fill text-danger" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
                                    </svg>
                                    {{$post->favourites()->count()}} Favourite

                                </button>
                            </form>&nbsp;
                            <button title="comments" class="btn btn-light"><i
                                    class="fa fa-comments"></i> {{$post->comments_count ??0}}</button>
                            <a href="{{route('blog::frontend.blog.categories.index',$post->category->slug)}}"
                               class="card-link">{{$post->category->title}}
                            </a>
                            @foreach($post->tags as $tag)
                                <a href="{{route('blog::frontend.blog.posts.index',['search'=>$tag->name])}}"> <span
                                        class="btn btn-light">{{$tag->name}}</span>
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
