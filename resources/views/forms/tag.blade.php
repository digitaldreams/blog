<form action="{{$route ?? route('blog::tags.store')}}" method="POST" class="m-form" onsubmit="return disableBtn()">
    {{csrf_field()}}
    <input type="hidden" name="_method" value="{{$method ?? 'POST'}}"/>
    <div class="m-form__group form-group row">
        <label for="title" class="col-form-label col-md-2">Name</label>
        <div class="col-md-9">
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
    </div>
    <div class="m-form__group  form-group row">
        <label for="slug" class="col-form-label col-md-2">Slug</label>
        <div class="col-md-9">
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
    <div class="m-form__group  form-group row">
        <label class="col-form-label col-md-2">Description</label>
        <div class="col-md-9">
            <textarea class="form-control" placeholder="describe about tag"
                      name="description">{{old('description',$model->description)}}</textarea>
        </div>
    </div>

    <div class="form-group text-right ">
        <input type="reset" class="btn btn-default" value="Clear"/>
        <input type="submit" class="btn btn-primary" value="Save"/>
    </div>
</form>