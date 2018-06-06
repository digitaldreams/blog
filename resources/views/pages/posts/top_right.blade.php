<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="category_id">Category</label>
            <select form="postForm" class="form-control" name="category_id" id="category_id">
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
    </div>

    <div class="col-sm-6">

        <div class="form-group">
            <label for="status">Status</label> <br/>
            <label class="radio-inline">
                <input type="radio" form="postForm" name="status"
                       value="{{\Blog\Models\Post::STATUS_DRAFT}}"
                        {{old('status',$model->status)==\Blog\Models\Post::STATUS_DRAFT?'checked':''}}
                >
                Draft
            </label>
            <label class="radio-inline">
                <input type="radio" form="postForm" name="status"
                       value="{{\Blog\Models\Post::STATUS_PUBLISHED}}"
                        {{old('status',$model->status)==\Blog\Models\Post::STATUS_PUBLISHED?'checked':''}}
                >
                Published
            </label>

            @if($errors->has('status'))
                <span class="form-control-feedback">
        <strong>{{ $errors->first('status') }}</strong>
    </span>
            @endif
        </div>

    </div>
</div>
<div class="form-group">
    <label for="image">Image</label>
    <input type="file" form="postForm" class="form-control {{ $errors->has('image') ? ' is-invalid' : '' }}"
           name="image" id="image"
           placeholder="Upload your image">
    @if($errors->has('image'))
        <span class="form-control-feedback">
        <strong>{{ $errors->first('image') }}</strong>
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

    @if($errors->has('image'))
        <span class="form-control-feedback">
        <strong>{{ $errors->first('image') }}</strong>
    </span>
    @endif
</div>