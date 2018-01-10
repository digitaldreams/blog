<form action="{{$route or route('blog::categories.store')}}" method="POST">
    {{csrf_field()}}
    <input type="hidden" name="_method" value="{{$method or 'POST'}}"/>
    <div class="row">
        <div class="col-sm-6 form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" id="title" value="{{old('title',$model->title)}}"
                   placeholder="" maxlength="255">
            @if($errors->has('title'))
                <span class="invalid-feedback">
        <strong>{{ $errors->first('title') }}</strong>
    </span>
            @endif
        </div>
        <div class="form-group col-sm-6">
            <label>Parent Category</label>
            <select name="parent_id" class="form-control">
                <option value="">None</option>
                @foreach($categories as $category)
                    <option value="{{$category->id}}" {{$category->id == $model->parent_id ? 'selected' : ''}}>{{$category->title}}</option>
                @endforeach
            </select>
        </div>

    </div>


    <div class="form-group ">
        <label for="slug">Slug</label>
        <input type="text" class="form-control {{ $errors->has('slug') ? ' is-invalid' : '' }} " name="slug" id="slug" value="{{old('slug',$model->slug)}}"
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