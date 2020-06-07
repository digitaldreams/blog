@extends(config('blog.layout'))
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('blog::types.index')}}">Types</a>
    </li>
    <li class="breadcrumb-item">
        {{$record->name}}
    </li>

@endsection

@section('tools')

    <a href="{{route('blog::types.create')}}">
        <span class="fa fa-plus"></span>
    </a>
    @can('update',$record)
        <a href="{{route('blog::types.edit',$record->id)}}">
            <span class="fa fa-pencil"></span>
        </a>
    @endcan
    @can('delete',$record)
        <form onsubmit="return confirm('Are you sure you want to delete?')"
              action="{{route('blog::types.destroy',$record->id)}}"
              method="post"
              style="display: inline">
            {{csrf_field()}}
            {{method_field('DELETE')}}
            <button type="submit" class="btn btn-default cursor-pointer  btn-sm">
                <i class="text-danger fa fa-remove"></i>
            </button>
        </form>
    @endcan

@endsection

@section('content')

    <div class="row">
        @foreach($activities as $activity)
            <div class="col-sm-4">
                @if($activity->activityable instanceof \Blog\Models\Post)
                    @include('blog::cards.post',['record'=>$activity->activityable])
                @elseif($activity->activityable instanceof \App\Models\WordMeaning)
                    @include('resources.views.cards.word_meaning',['record'=>$activity->activityable])
                @elseif($activity->activityable instanceof \Exam\Models\Exam)
                    @include('exam::cards.exam',['record'=>$activity->activityable])
                @endif
            </div>
        @endforeach
    </div>
    <div class="row">
        <div class="col-sm-12">
            {!! $activities->render() !!}
        </div>
    </div>
@endSection
