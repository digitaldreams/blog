<div class="card bg-light mb-2">
  <div class="header">
    <div class="left-header">
      <div class="author-name">
        <a href="#"><i class="fa fa-user"></i> {{$record->user->name}}</a> 
      </div>
    </div>
    <div class="right-header">
      <span class="mdi mdi-dots-horizontal show-dot-menu"></span>
     
      <div class="dot-menu-grid">
        <div class="ripple"></div>
        @can('update',$record)
          <div class="row">
            <div class="cell">
              <a class="card-link" href="{{route('blog::posts.edit',$record->slug)}}">
                <i class="mdi mdi-table-edit"></i>
                <div class="text">Edit</div>
              </a>
            </div>
          </div>
        @endcan
        <div class="row">
          <div class="cell">
            @include('blog::forms.destroy',['route'=>route('blog::posts.destroy',$record->slug)])
          </div>
        </div>  
      </div>      
    </div>
  </div>
  <div class="body">
    <a href="{{route('blog::posts.show',$record->slug)}}">
      <img class="card-img-top" src="{{$record->getImageUrl()}}" alt="{{$record->title}}">
    </a>
    <div class="card-link">
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
    </div>
  </div>
  <div class="footer">
    <div class="like">
      <form action="{{route('blog::activities.store')}}" method="post" class="d-inline">
          {{csrf_field()}}
        <input type="hidden" name="activityable_type" value="{{get_class($record)}}">
        <input type="hidden" name="activityable_id" value="{{$record->id }}">
        <input type="hidden" name="type" value="like">
        <button class="btn btn-black">
          <i class="fa fa-thumbs-up"></i> {{$record->likes()->count()}}
        </button>
      </form>
    </div>
    <div class="comment">
      <a href="#">
        <i class="fa fa-comment"></i> {{$record->comments_count}}
      </a>
    </div>
    <div class="view">
      <a href="#">
        <i class="fa fa-eye"></i> 
        {{$record->total_view}}
      </a>
    </div>
    <div class="favourites">
      <form action="{{route('blog::activities.store')}}" method="post" class="d-inline">
      {{csrf_field()}}
        <input type="hidden" name="activityable_type" value="{{get_class($record)}}">
        <input type="hidden" name="activityable_id" value="{{$record->id }}">
        <input type="hidden" name="type" value="favourite">
        <button class="btn btn-black">
          <i class="fa fa-star"></i> {{$record->favourites()->count()}}
        </button>
      </form>
    </div>
  </div>
</div>
