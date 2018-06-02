<div class="card bg-light mb-1">
    <a style="border: 0;color:white" href="{{route('blog::posts.show',$record->slug)}}">
        <img class="card-img-top" height="150px" src="{{$record->getImageUrl()}}" alt="{{$record->title}}">
    </a>
    <div class="card-title h-6 p-1">
        <a href="{{route('blog::posts.show',$record->slug)}}"> {{$record->title}}</a>
    </div>
</div>
