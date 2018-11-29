@extends(config('blog.layout.show'))

@section('content')
<div class="container">
 	<div class="row">
 		@foreach($userPosts as $userPost)
			@include('blog::cards.single_user_post')
		@endforeach
	</div>
</div>
@endsection