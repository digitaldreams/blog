<form action="{{$route ?? route('blog::tags.store')}}" method="POST">
    {{csrf_field()}}
    <input type="hidden" name="_method" value="{{$method ?? 'POST'}}"/>
    <div class="form-row">
        <div class="form-group col">
            <label for="title">Name</label>
            <input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" name="name"
                   id="name"
                   value="{{old('name',$model->name)}}"
                   placeholder="e.g. Sentence" maxlength="191">
            @if($errors->has('name'))
                <span class="invalid-feedback">
        <strong>{{ $errors->first('name') }}</strong>
    </span>
            @endif
        </div>
        <div class="form-group col">
            <label for="slug">Slug</label>
            <input type="text" class="form-control {{ $errors->has('slug') ? ' is-invalid' : '' }}" name="slug"
                   id="slug"
                   value="{{old('slug',$model->slug)}}"
                   placeholder="If blank title will be used as slug" maxlength="150">
            @if($errors->has('slug'))
                <span class="invalid-feedback ">
        <strong>{{ $errors->first('slug') }}</strong>
    </span>
            @endif
        </div>
    </div>
    <div class="form-group">
        <label>Description</label>
        <textarea class="form-control" name="description">{{old('description',$model->description)}}</textarea>
    </div>

    <div class="form-group text-right ">
        <input type="reset" class="btn btn-default" value="Clear"/>
        <input type="submit" class="btn btn-primary" value="Save"/>
    </div>
</form>