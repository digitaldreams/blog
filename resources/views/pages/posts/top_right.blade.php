<div class="mb-3">
    <label for="category_id">Category
        <span class="fa fa-info-circle" data-toggle="tooltip" title="{{trans('blog::info.category')}}"></span>
    </label>
    <select form="postForm" class="form-control" name="category_id" id="category_id" required>
        @if($model->category_id)
            <option value="{{$model->category_id}}" selected>{{$model->category->title}}</option>
        @endif
    </select>
    @if($errors->has('category_id'))
        <span class="form-control-feedback">
        <strong>{{ $errors->first('category_id') }}</strong>
    </span>
    @endif
</div>
@can('approve',\Blog\Models\Post::class)
    <div class="mb-3">
        <label for="status">Status</label>
        <span class="fa fa-info-circle" data-toggle="tooltip" title="{{trans('blog::info.postStatus')}}"></span>
        <br/>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="status" form="postForm" id="inlineRadio1"
                   value="{{\Blog\Models\Post::STATUS_DRAFT}}" {{old('status',$model->status)==\Blog\Models\Post::STATUS_DRAFT?'checked':''}}>
            <label class="form-check-label" for="inlineRadio1">Draft</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="status" form="postForm" id="inlineRadio2"
                   value="{{\Blog\Models\Post::STATUS_PUBLISHED}}" {{old('status',$model->status)==\Blog\Models\Post::STATUS_PUBLISHED?'checked':''}}>
            <label class="form-check-label" for="inlineRadio2">Published</label>
        </div>

        @if($errors->has('status'))
            <span class="form-control-feedback">
        <strong>{{ $errors->first('status') }}</strong>
    </span>
        @endif
    </div>


    <div class="mb-3">
        <div class="form-check form-check-inline">
            <input class="form-check-input" name="is_featured" type="checkbox" form="postForm" id="inlineCheckbox1"
                   value="1" {{old('is_featured',$model->is_featured)==\Blog\Models\Post::IS_FEATURED?'checked':''}}>
            <label class="form-check-label" for="inlineCheckbox1"> Featured Post
            </label>
            &nbsp; <span class="fa fa-info-circle" data-toggle="tooltip"
                         title="{{trans('blog::info.featuredPost')}}"></span>
        </div>

        @if($errors->has('is_featured'))
            <span class="form-control-feedback">
        <strong>{{ $errors->first('is_featured') }}</strong>
    </span>
        @endif
    </div>
@endcan
<div class="mb-3">
    <label for="image">Tags
        <span class="fa fa-info-circle" data-toggle="tooltip" title="{{trans('blog::info.tags')}}"></span>
    </label>
    <select form="postForm" class="form-control {{ $errors->has('tags') ? ' is-invalid' : '' }}"
            name="tags[]" id="blog_tags" multiple>
        @if(!empty($model->tags))
            @foreach($model->tags as $tag)
                <option value="{{$tag->name}}" selected>{{$tag->name}}</option>
            @endforeach
        @endif
    </select>
</div>

<div class="mb-3">
    <label for="image">Feature Image</label>
    <input type="file" form="postForm" class="form-control {{ $errors->has('image') ? ' is-invalid' : '' }}"
           name="image" id="image"
           onchange="checkSize(2097152,'image','image')"
           {{empty($model->id)?'required':''}}
           accept="image/*"
           {{empty($model->id?'required':'')}}
           placeholder="Upload your image">
    @if($errors->has('image'))
        <span class="form-control-feedback">
        <strong>{{ $errors->first('image') }}</strong>
    </span>
    @endif
    <p class="help-block">450px X 304px and maximum 2 MB allowed</p>

    <img src="{{$model->getImageUrl()}}" class="img-thumbnail" style="max-height: 300px" id="image_preview">
    @if(is_object($model->image))
        @can('update',$model->image)
            <div class="btn-group btn-group-sm text-right">
                <a class="btn btn-outline-secondary"
                   href="{{route('photo::photos.edit',$model->image_id)}}?returnUrl={{request()->fullUrl()}}">
                    <span class="fa fa-pencil-alt"> Edit </span>
                </a>
                <a class="btn btn-outline-secondary"
                   href="{{route('photo::photos.show',$model->image_id)}}?returnUrl={{request()->fullUrl()}}">
                    <span class="fa fa-crop"> Crop</span>
                </a>
            </div>
        @endcan
    @endif
    @if($errors->has('image'))
        <span class="form-control-feedback">
        <strong>{{ $errors->first('image') }}</strong>
    </span>
    @endif
</div>
