@extends(config('blog.layout'))
@section('breadcrumb')
    <li class="breadcrumb-item">
        Preferences
    </li>
@endsection
@section('header')
    Select Your Preferences
@endsection
@section('tools')
    <input type="submit" class="btn btn-primary" value="Save" form="preferencesForm">
@endsection
@section('content')
    <form action="{{route('blog::preferences.store')}}" method="post" id="preferencesForm">
        {{csrf_field()}}
        <input type="hidden" name="returnUrl" value="{{request('return')}}">
        <h3>Categories</h3>
        <div class="row">
            @foreach($categories->chunk(10) as $chunkedCategories)
                <div class="col-sm-3">
                    <ul class="list-group list-group-flush">
                        @foreach($chunkedCategories as $category)
                            <li class="list-group-item">
                                <div class="form-check">

                                    <input class="form-check-input" id="user_category_{{$category->id}}" type="checkbox"
                                           value="{{$category->id}}"
                                           {{in_array($category->id,$userCategories)?'checked':''}} name="categories[]">
                                    <label class="form-check-label" for="user_category_{{$category->id}}">
                                        {{$category->title}}
                                    </label>
                                </div>
                                @if(!empty($category->children))
                                    <ul class="list-group list-group-flush">
                                        @foreach($category->children as $childCategory)
                                            <li class="list-group-item my-1 py-1">
                                                <div class="form-check">

                                                    <input class="form-check-input"
                                                           id="user_child_category_{{$childCategory->id}}"
                                                           type="checkbox"
                                                           value="{{$childCategory->id}}"
                                                           {{in_array($childCategory->id,$userCategories)?'checked':''}}
                                                           name="categories[]">
                                                    <label class="form-check-label"
                                                           for="user_child_category_{{$childCategory->id}}">
                                                        {{$childCategory->title}}
                                                    </label>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>

                </div>
            @endforeach

        </div>
        <h3>
            Tags
        </h3>
        <div class="row">
            @foreach($tags->chunk(10) as $chunkedTags)
                    @foreach($chunkedTags as $tag)
                            <div class="form-check">
                                <input class="form-check-input" id="user_tags_{{$tag->id}}" type="checkbox"
                                       value="{{$tag->id}}"
                                       {{in_array($tag->id,$userTags)?'checked':''}} name="tags[]">
                                <label class="form-check-label" for="user_tags_{{$tag->id}}">
                                    {{$tag->name}}
                                </label>
                            </div>
                    @endforeach
            @endforeach
        </div>
        <div class="text-right">
            <input type="submit" class="btn btn-primary" value="Save">
        </div>
    </form>
@endSection

@section('script')
@endsection
