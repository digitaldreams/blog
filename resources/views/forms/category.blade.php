<form action="{{$route or route('categories.store')}}" method="POST">
    {{csrf_field()}}
    <input type="hidden" name="_method" value="{{$method or 'POST'}}"/>
    <div class="form-group {{ $errors->has('title') ? ' has-danger' : '' }}">
        <label for="title">Title</label>
        <input type="text" class="form-control" name="title" id="title" value="{{old('title',$model->title)}}"
               placeholder="" maxlength="255">
        @if($errors->has('title'))
            <span class="invalid-feedback">
        <strong>{{ $errors->first('title') }}</strong>
    </span>
        @endif
    </div>

    <div class="form-group {{ $errors->has('slug') ? ' has-danger' : '' }}">
        <label for="slug">Slug</label>
        <input type="text" class="form-control is-invalid" name="slug" id="slug" value="{{old('slug',$model->slug)}}"
               placeholder="" maxlength="255" required="required">
        @if($errors->has('slug'))
            <span class="invalid-feedback">
        <strong>{{ $errors->first('slug') }}</strong>
    </span>
        @endif
    </div>


    <div class="form-group text-right ">
        <input type="reset" class="btn btn-default" value="Clear"/>
        <input type="submit" class="btn btn-primary" value="Save"/>

    </div>
</form>