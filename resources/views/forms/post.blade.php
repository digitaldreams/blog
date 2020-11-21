<form action="{{$route ?? route('blog::posts.store')}}" method="POST" enctype="multipart/form-data" id="postForm"
      onsubmit="return disableBtn()">
    {{csrf_field()}}
    <input type="hidden" name="_method" value="{{$method ?? 'POST'}}"/>


    <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" class="form-control {{$errors->has('title')?'is-invalid':''}}" name="title" id="title"
               value="{{old('title',$model->title)}}"
               required
               placeholder="Type a unique post title." maxlength="190">
        @if($errors->has('title'))
            <span class="form-control-feedback">
                <strong>{{ $errors->first('title') }}</strong>
            </span>
        @endif
    </div>


    <div class="form-group {{ $errors->has('body') ? ' has-danger' : '' }}">
        <textarea class="form-control bootstrap-summernote-editor" rows="40" cols="20" name="body" required
                  id="summernote">{{old('body',$model->body)}}</textarea>
        @if($errors->has('body'))
            <span class="form-control-feedback">
        <strong>{{ $errors->first('body') }}</strong>
    </span>
        @endif
    </div>

    {!! \SEO\Seo::form($model) !!}

    <div class="text-right mt-2 ">
        <input type="reset" class="btn btn-default" value="Clear"/>
        <input type="submit" class="btn btn-primary" value="Save"/>

    </div>

</form>
