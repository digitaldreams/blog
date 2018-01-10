<form action="{{$route or route('blog::posts.store')}}" method="POST" enctype="multipart/form-data">
    {{csrf_field()}}
    <input type="hidden" name="_method" value="{{$method or 'POST'}}"/>


    <div class="form-row">
        <div class="form-group col-sm-8">
            <label for="title">Title</label>
            <input type="text" class="form-control" name="title" id="title" value="{{old('title',$model->title)}}"
                   placeholder="" maxlength="255">
            @if($errors->has('title'))
                <span class="form-control-feedback">
        <strong>{{ $errors->first('title') }}</strong>
    </span>
            @endif
        </div>
        <div class="form-group col-sm-4">
            <label for="slug">Slug</label>
            <input type="text" class="form-control" name="slug" id="slug" value="{{old('slug',$model->slug)}}"
                   placeholder="By default title will be used as slug" maxlength="255">
            @if($errors->has('slug'))
                <span class="form-control-feedback">
        <strong>{{ $errors->first('slug') }}</strong>
    </span>
            @endif
        </div>
        <div class="form-group col-sm-4">

        </div>
    </div>


<!-- <div class="form-group {{ $errors->has('status') ? ' has-danger' : '' }}">
        <label for="status">Status</label>
        <input type="text" class="form-control" name="status" id="status" value="{{old('status',$model->status)}}"
               placeholder="" maxlength="255" required="required">
        @if($errors->has('status'))
    <span class="form-control-feedback">
<strong>{{ $errors->first('status') }}</strong>
    </span>
        @endif
        </div> -->

    <div class="form-group {{ $errors->has('body') ? ' has-danger' : '' }}">
        <textarea class="form-control" rows="20" cols="20" name="body"
                  id="summernote">{{old('body',$model->content)}}</textarea>
        @if($errors->has('body'))
            <span class="form-control-feedback">
        <strong>{{ $errors->first('body') }}</strong>
    </span>
        @endif
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group {{ $errors->has('category_id') ? ' has-danger' : '' }}">
                <label for="category_id">Category</label>
                <select class="form-control" name="category_id" id="category_id">
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
            <div class="form-group {{ $errors->has('image') ? ' has-danger' : '' }}">
                <label for="image">Image</label>
                <input type="file" class="form-control" name="image" id="image"
                       placeholder="Upload your image">
                @if($errors->has('image'))
                    <span class="form-control-feedback">
        <strong>{{ $errors->first('image') }}</strong>
    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="form-group text-right ">
        <input type="reset" class="btn btn-default" value="Clear"/>
        <input type="submit" class="btn btn-primary" value="Save"/>

    </div>
</form>