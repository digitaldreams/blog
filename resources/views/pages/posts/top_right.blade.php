<div class="m-form__group form-group">
    <label for="category_id">Category</label>
    <select form="postForm" class="form-control" name="category_id" id="category_id" required>
        @if(isset($categories))
            @foreach ($categories as $data)
                <option value="{{$data->id}}">{{$data->title}}</option>;
            @endforeach
        @endif

    </select>
    @if($errors->has('category_id'))
        <span class="form-control-feedback">
        <strong>{{ $errors->first('category_id') }}</strong>
    </span>
    @endif
</div>

<div class="form-group">
    <label for="status">Status</label> <br/>
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

<div class="form-group">
    <div class="form-check form-check-inline">
        <input class="form-check-input" name="is_featured" type="checkbox" form="postForm" id="inlineCheckbox1"
               value="1" {{old('is_featured',$model->is_featured)==\Blog\Models\Post::IS_FEATURED?'checked':''}}>
        <label class="form-check-label" for="inlineCheckbox1"> Featured Post
        </label>
    </div>

    @if($errors->has('is_featured'))
        <span class="form-control-feedback">
        <strong>{{ $errors->first('is_featured') }}</strong>
    </span>
    @endif
</div>
<div class="form-group">
    <label for="image">Tags</label>
    <select form="postForm" class="form-control {{ $errors->has('tags') ? ' is-invalid' : '' }}"
            name="tags[]" id="blog_tags" multiple>
        @foreach($tags as $tag)
            <option value="{{$tag->id}}" {{in_array($tag->id,$model->tagIds())?'selected':''}}>{{$tag->name}}</option>
        @endforeach
    </select>


</div>
<div class="form-group">
    <label for="image">Image</label>
    <input type="file" form="postForm" class="form-control {{ $errors->has('image') ? ' is-invalid' : '' }}"
           name="image" id="image"
           onchange="return checkSize(2097152,'image')"
           {{empty($model->id)?'required':''}}
           accept="image/*"
           placeholder="Upload your image">
    @if($errors->has('image'))
        <span class="form-control-feedback">
        <strong>{{ $errors->first('image') }}</strong>
    </span>
    @endif
    <p class="help-block">450px X 304px and maximum 2 MB allowed</p>

    <img src="{{$model->getImageUrl()}}" class="img-thumbnail" style="max-height: 300px" id="image_preview">

    @if($errors->has('image'))
        <span class="form-control-feedback">
        <strong>{{ $errors->first('image') }}</strong>
    </span>
    @endif
</div>
