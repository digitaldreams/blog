@extends(config('blog.layout'))
@section('breadcrumb')
    @foreach($model->breadcrumb() as $text=>$link)
        <li class="breadcrumb-item">
            <a href="{{$link}}">{{$text}}</a>
        </li>
    @endforeach
@endsection
@section('header')
    <h3>
        <i class="fa fa-pencil" style="font-size: 18px"></i>
        @if(method_exists($model,'title'))
            {{$model->title()}}
        @else
            {{$model->title}}
        @endif
        <span class="badge badge-light">{{$action}} </span>
    </h3>
@endsection

@section('tools')


@endsection

@section('content')
    <div class="m-widget3">
        @if(count($activities)>0)

            @foreach($activities as $activity)
                <div class="m-widget3__item">
                    <div class="m-widget3__header">
                        <div class="m-widget3__user-img">
                            <img class="m-widget3__img" src="{{$activity->user->getAvatarThumb()}}"
                                 alt="{{$activity->user->getFullName()}}">
                        </div>
                        <div class="m-widget3__info">
														<span class="m-widget3__username">
														<a href="{{route('conversations.show',$activity->id)}}">

                                                            {{$activity->user->name}} <span
                                                                    class="text-gray-dark"> </span>
                                                             </a>
														</span><br>
                            <span class="m-widget3__time">
															{{$activity->created_at->diffForHumans()}}
														</span>
                        </div>
                        <span class="m-widget3__status m--font-info">

														{{$activity->type}}
													</span>
                    </div>
                    <div class="m-widget3__body">
                        <p class="m-widget3__text">
                            @if($activity->type==\Blog\Models\Activity::TYPE_INAPPROPRIATE)
                                <label class="badge badge-light">{{$activity->reason}}</label>
                                {{$activity->message}}
                            @endif
                        </p>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    @foreach($activities as $activity)

    @endforeach
@endSection
