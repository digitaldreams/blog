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
                                <label class="form-check">
                                    <input type="checkbox" value="{{$category->id}}"
                                           {{in_array($category->id,$userCategories)?'checked':''}} name="categories[]">
                                    {{$category->title}}
                                </label>
                                @if(!empty($category->children))
                                    <ul class="list-group list-group-flush">
                                        @foreach($category->children as $childCategory)
                                            <li class="list-group-item my-1 py-1">
                                                <label class="form-check">
                                                    <input type="checkbox" value="{{$childCategory->id}}"
                                                           {{in_array($childCategory->id,$userCategories)?'checked':''}}
                                                           name="categories[]">
                                                    {{$childCategory->title}}
                                                </label>
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
                <ul class="list-group list-group-flush">
                    @foreach($chunkedTags as $tag)
                        <li class="list-group-item my-0 py-0">
                            <label class="form-check">
                                <input type="checkbox" value="{{$tag->id}}"
                                       {{in_array($tag->id,$userTags)?'checked':''}} name="tags[]">
                                {{$tag->name}}
                            </label>
                        </li>
                    @endforeach
                </ul>
            @endforeach
        </div>
        <div class="form-group text-right">
            <input type="submit" class="btn btn-primary" value="Save">
        </div>
    </form>
@endSection

@section('script')
@endsection
