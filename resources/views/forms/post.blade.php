<form action="{{$route ?? route('blog::posts.store')}}" method="POST" enctype="multipart/form-data" id="postForm">
    {{csrf_field()}}
    <input type="hidden" name="_method" value="{{$method ?? 'POST'}}"/>


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
        <div class="form-group col-sm-3">
            <label for="slug">Slug</label>
            <input type="text" class="form-control" name="slug" id="slug" value="{{old('slug',$model->slug)}}"
                   placeholder="By default title will be used as slug" maxlength="255">
            @if($errors->has('slug'))
                <span class="form-control-feedback">
        <strong>{{ $errors->first('slug') }}</strong>
    </span>
            @endif
        </div>
        <div class="form-group col-sm-1 pt-4">
            <button type="submit" class="mt-1 btn btn-block btn-outline-primary"><i class="fa fa-save"></i> Save
            </button>
        </div>
    </div>


    <div class="form-group {{ $errors->has('body') ? ' has-danger' : '' }}">
        <textarea class="form-control" rows="40" cols="20" name="body"
                  id="summernote">{{old('body',$model->content)}}</textarea>
        @if($errors->has('body'))
            <span class="form-control-feedback">
        <strong>{{ $errors->first('body') }}</strong>
    </span>
        @endif
    </div>

    {!! \SEO\Seo::form($model) !!}

    <div class="form-group text-right mt-2 ">
        <input type="reset" class="btn btn-default" value="Clear"/>
        <input type="submit" class="btn btn-primary" value="Save"/>

    </div>

</form>