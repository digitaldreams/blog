
		<div class="col-md-4">
			<div class="lavel">
				<div class="image-resize"><a href="{{route('blog::posts.show',$userPost->slug)}}">
         <img class="card-img-top" src="{{asset('img/'.$userPost->image)}}" alt="{{$userPost->title}}">
      </a>  </div>			
				<h2>{{$userPost->title}}</h2>
			</div>

		</div>