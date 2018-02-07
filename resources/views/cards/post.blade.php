<div class="card bg-dark mb-2">
    <a style="border: 0;color:white" href="{{route('blog::posts.show',$record->slug)}}">
        <img class="card-img-top" src="{{$record->getImageUrl()}}" alt="{{$record->title}}">
    </a>
    <div class="card-body">
        <div class="card-title">
            <h3 class="h6">
                <a href="{{route('blog::posts.show',$record->slug)}}"> {{$record->title}}</a>

            </h3>
        </div>
        <p class="card-text text-muted text-right">Under
            <a href="{{route('blog::categories.show',$record->category->slug)}}"> {{$record->category->title}}</a>
            by {{$record->user->name}}
            at {{$record->created_at->diffForHumans()}}
            @if(auth()->check())
                <a class="card-link" href="{{route('blog::posts.edit',$record->slug)}}">
                    <span class="fa fa-pencil"></span>
                </a>
                @include('blog::forms.destroy',['route'=>route('blog::posts.destroy',$record->slug)])
            @endif
        </p>


    </div>
</div>
