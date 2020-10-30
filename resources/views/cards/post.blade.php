<div class="card bg-light mb-3">
    <a style="border: 0;" href="{{route('blog::posts.show',$record->slug)}}">
        @if($record->image)
            {!! $record->image->renderThumbnails() !!}
        @else
            <img src="{{config('blog.defaultPhoto')}}" class="card-img-top">
        @endif
    </a>
    <div class="card-body">
        <div class="card-title">
            <div class="d-flex flex-row justify-content-between">
                <h4>
                    <a href="{{route('blog::frontend.blog.posts.show',['category'=>$record->category->slug,'post'=>$record->slug])}}"> {{$record->title}}</a>
                </h4>
                @can('update',$record)
                    @include('blog::includes.post_dropdown_menu')
                @endcan
            </div>
         </div>
    </div>
    <div class="card-footer text-right" style="font-size: 13px" title="{{$record->created_at->diffForHumans()}}">

        <a href="#"><i class="fa fa-user"></i> {{$record->user->name ??'someone'}}</a> &nbsp;
        <label class="badge badge-light">
            <i class="fa fa-comment-o"></i> {{$record->comments_count}}
        </label>
        <label class="badge badge-light">
            <i class="fa fa-eye"></i> {{$record->total_view}}
        </label>
        &nbsp;<form action="{{route('blog::activities.store')}}" method="post" class="d-inline">
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
</div>
