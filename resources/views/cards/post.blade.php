<div class="card bg-light mb-3">
    <div class="row no-gutters">
        <div class="col-3">
            <a style="border: 0;" href="{{route('blog::posts.show',$record->slug)}}">
                @if($record->image)
                    {!! $record->image->renderThumbnails('card-img') !!}
                @else
                    <img src="{{config('blog.defaultPhoto')}}" class="card-img" style="object-position: center">
                @endif
            </a>
        </div>
        <div class="col-9">
            <div class="card-body">
                <div class="card-title">
                    <div class="d-flex flex-row justify-content-between">
                        <h4>
                            @if($record->status==\Blog\Models\Post::STATUS_PUBLISHED)
                                <i class="fa fa-check-circle text-primary"></i>
                            @endif
                            <a href="{{route('blog::frontend.blog.posts.show',['category'=>$record->category->slug,'post'=>$record->slug])}}"> {{$record->title}}</a>
                        </h4>
                        @can('update',$record)
                            @include('blog::includes.post_dropdown_menu')
                        @endcan
                    </div>
                </div>
                <div class="">
                    @if($record->category)
                        <a href="?search={{$record->category->title}}">{{$record->category->title}}</a>
                    @endif
                    @foreach($record->tags as $tag)
                        <a class="badge bg-secondary link-light" href="?search={{$tag->name}}">{{$tag->name}}</a>
                    @endforeach
                </div>
            </div>

        </div>
        <div class="card-footer text-right" style="font-size: 13px" title="{{$record->created_at->diffForHumans()}}">

            <a href="#">
                <i class="fa fa-user"></i> {{$record->user->name ??'someone'}}
            </a> &nbsp;
            <label class="btn btn-outline-secondary btn-sm">
                <i class="fa fa-comment-o"></i> {{$record->comments()->count()}}
            </label>

            &nbsp;<form action="{{route('blog::activities.store')}}" method="post" class="d-inline">
                {{csrf_field()}}
                <input type="hidden" name="activityable_type" value="{{get_class($record)}}">
                <input type="hidden" name="activityable_id" value="{{$record->id }}">
                <input type="hidden" name="type" value="like">
                <button class="btn btn-outline-secondary  btn-sm">
                    <i class="fa fa-thumbs-up"></i> {{$record->likes()->count()}}
                </button>
            </form>
            <form action="{{route('blog::activities.store')}}" method="post" class="d-inline">
                {{csrf_field()}}
                <input type="hidden" name="activityable_type" value="{{get_class($record)}}">
                <input type="hidden" name="activityable_id" value="{{$record->id }}">
                <input type="hidden" name="type" value="favourite">
                <button class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-star"></i> {{$record->favourites()->count()}}
                </button>
            </form>&nbsp;

            <label class="btn btn-outline-secondary  btn-sm">
                <i class="fa fa-eye"></i> {{$record->total_view}}
            </label>

        </div>
    </div>


</div>
