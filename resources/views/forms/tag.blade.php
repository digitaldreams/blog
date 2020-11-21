<form action="{{$route ?? route('blog::tags.store')}}" method="POST" class="m-form" onsubmit="return disableBtn()">
    {{csrf_field()}}
    <input type="hidden" name="_method" value="{{$method ?? 'POST'}}"/>
    <div class="mb-3 row">
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

    <div class="mb-3 row">
        <label class="col-form-label col-md-2">Description</label>
        <div class="col-md-9">
            <textarea class="form-control" placeholder="describe about tag"
                      name="description">{{old('description',$model->description)}}</textarea>
        </div>
    </div>

    <div class="text-right ">
        <input type="reset" class="btn btn-default" value="Clear"/>
        <input type="submit" class="btn btn-primary" value="Save"/>
    </div>
</form>
