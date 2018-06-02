<div class="card bg-light mb-2">
    <a style="border: 0;" href="{{route('blog::posts.show',$record->slug)}}">
        <img class="card-img-top" src="{{$record->getImageUrl()}}" alt="{{$record->title}}">
    </a>
    <div class="card-body">
        <div class="card-title">
            <h3 class="h6">
                <a href="{{route('blog::posts.show',$record->slug)}}"> {{$record->title}}</a>

            </h3>
        </div>

    </div>
    <div class="card-footer text-right" title="{{$record->created_at->diffForHumans()}}">

        <a href="#"><i class="fa fa-user"></i>  {{$record->user->name}}</a>  &nbsp;
        <label class="badge badge-light">
            <i class="fa fa-comment-o"></i> {{$record->comments_count}}
        </label>
        <label class="badge badge-light">
            <i class="fa fa-eye"></i> {{$record->total_view}}
        </label>
        &nbsp;&nbsp;
        @if(auth()->check())
            <a class="card-link" href="{{route('blog::posts.edit',$record->slug)}}">
                <span class="fa fa-pencil"></span>
            </a>
            @include('blog::forms.destroy',['route'=>route('blog::posts.destroy',$record->slug)])
        @endif
    </div>
</div>
