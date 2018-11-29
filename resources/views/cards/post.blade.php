<!-- <div class="card bg-light mb-2 level">
   <a style="border: 0;" href="{{route('blog::posts.show',$record->slug)}}">
      <img class="card-img-top" src="{{asset('img/'.$record->image)}}" alt="{{$record->title}}">
   </a>
   <div class="card-body">
      <div class="card-title">
         <h3 class="h6">
         <a href="{{route('blog::posts.show',$record->slug)}}"> {{$record->title}}</a>
         <div class="dropdown d-inline" id="dropdown-{{$record->id}}">
            <a href="#" class="fa fa-ellipsis-v" data-toggle="dropdown" role="button"
          aria-expanded="false">
         </a>
         <ul class="dropdown-menu p-3 pt-0" role="menu">
            <form action="{{route('blog::activities.store')}}" method="post">
           {{csrf_field()}}
               <input type="hidden" name="activityable_type" value="{{get_class($record)}}">
               <input type="hidden" name="activityable_id" value="{{$record->id }}">
           @foreach(\Blog\Models\Activity::actions() as $key=>$activity)
           <li>
            <input class="btn btn-block btn-light" name="type" type="submit" value="{{$key}}"
            value="{{$activity}}">
         </li>
         @endforeach
      </form>
   </ul>
</div>
</h3>
</div>
</div>
<div class="card-footer text-right" title="{{$record->created_at->diffForHumans()}}">

 <a href="#"><i class="fa fa-user"></i> 
   
   {{$record->user->name}}</a> &nbsp;
 <label class="badge badge-light"><p>
   <i class="fa fa-comment-o"></i> {{$record->comments_count}}</p>
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
@can('update',$record)
<a class="card-link" href="{{route('blog::posts.edit',$record->slug)}}">
  <span class="fa fa-pencil"></span>
</a>
@include('blog::forms.destroy',['route'=>route('blog::posts.destroy',$record->slug)])
@endcan

</div>
</div> -->




<div class="level">
   <div class="image-resize">
      <a href="{{route('blog::posts.show',$record->slug)}}">
         
         <img src="{{asset('img/'.$record->image)}}" alt="">
      </a>  
   </div>
   <h2>
      <a href="{{route('blog::posts.show',$record->slug)}}"> {{$record->title}}</a>
   </h2>
   <p>{{$record->body}}</p>
   <div class="blog-footer" title="{{$record->created_at->diffForHumans()}}">
      <div class="user">
         <a href="{{ route('blog::userPost', [$record->user->id]) }}">
            <div><i class="fa fa-user"></i> 
               {{$record->user->first_name}}
            </div>
         </a>
      </div>
      <div class="blog-info">
         <label class="badge badge-light">
            <p>
           <i class="fa fa-comment"></i> {{$record->comments->count()}}</p>
        </label>
         <label class="badge badge-light">
            <p><i class="fa fa-eye"></i> {{$record->total_view}}</p>
         </label>
         <form action="{{route('blog::activities.store')}}" method="post" class="d-inline">
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
         
         </form>
      </div>
      <div class="permission">
         @can('update',$record)
            <a href="{{route('blog::posts.edit',$record->slug)}}">
               <span> 
                  <i class="far fa-edit"></i>
               </span>
            </a>
            @include('blog::forms.destroy',['route'=>route('blog::posts.destroy',$record->slug)])
         @endcan
      </div>
   </div>
</div>

