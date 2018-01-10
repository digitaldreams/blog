<div class="card card-default">
    <img class="card-img-top" src="{{$record->getImageUrl()}}" alt="{{$record->title}}">
    <div class="card-header">
        <div class="row">
            <div class="col-sm-9">
                <h3><a href="{{route('blog::posts.show',$record->slug)}}"> {{$record->title}}</a></h3>
            </div>
            <div class="col-sm-3 text-right">
                @if(auth()->check())
                    <div class="btn-group" style="float: left">
                        <a href="{{route('blog::posts.edit',$record->slug)}}">
                            <span class="fa fa-pencil"></span>
                        </a>
                        <form onsubmit="return confirm('Are you sure you want to delete?')"
                              action="{{route('blog::posts.destroy',$record->slug)}}"
                              method="post" style="display: inline">
                            {{csrf_field()}}
                            {{method_field('DELETE')}}
                            <button type="submit" class="btn btn-default cursor-pointer  btn-sm"><i
                                        class="text-danger fa fa-remove"></i></button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="card-block">
        <p class="card-text text-muted text-right">Under
            <a href="{{route('blog::categories.show',$record->category->slug)}}"> {{$record->category->title}}</a>
            by {{$record->user->name}}
            at {{$record->created_at->diffForHumans()}}</p>

    </div>
</div>
