<form action="{{$route ?? route('blog::categories.store')}}" method="POST" class="m-form"
      onsubmit="return disableBtn()">
    {{csrf_field()}}
    <input type="hidden" name="_method" value="{{$method ?? 'POST'}}"/>
    <div class="m-form__group form-group row">
        <label for="title" class="col-form-label col-md-2">Title</label>
        <div class="col-md-9">
            <input type="text" class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}" name="title"
                   id="title"
                   value="{{old('title',$model->title)}}"
                   placeholder="e.g. Accommodation" maxlength="255">
            @if($errors->has('title'))
                <span class="invalid-feedback">
        <strong>{{ $errors->first('title') }}</strong>
    </span>
            @endif
        </div>
    </div>
    <div class="m-form__group form-group row">
        <label for="parent_id" class="col-form-label col-md-2">Parent </label>
        <div class="col-md-9">
            <select class="form-control {{ $errors->has('parent_id') ? ' is-invalid' : '' }}" name="parent_id"
                    id="parent_id">
                <option value="">None</option>
                @foreach($categories as $category)
                    <option
                        value="{{$category->id}}" {{$category->id==old('parent_id',$model->parent_id)?'selected':''}}>{{$category->title}}</option>

                    @if($category->children->count()>0)
                        <optgroup label="Child of {{$category->title}}">

                            @foreach($category->children as $child)
                                <option
                                    value="{{$child->id}}" {{$child->id==old('parent_id',$model->parent_id)?'selected':''}}>
                                    &nbsp;{{$child->title}}</option>

                                @if($child->children->count()>0)
                                    <optgroup label="&nbsp;&nbsp;&nbsp;&nbsp; Child of {{$child->title}}">
                                        @foreach($child->children as $grandChild)
                                            <option
                                                value="{{$grandChild->id}}" {{$grandChild->id==old('parent_id',$model->parent_id)?'selected':''}}>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$grandChild->title}}</option>
                                        @endforeach
                                    </optgroup>
                                @endif

                            @endforeach
                        </optgroup>
                    @endif
                @endforeach
            </select>
            @if($errors->has('parent_id'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('parent_id') }}</strong>
                 </span>
            @endif
        </div>
    </div>

    <div class="form-group text-right ">
        <input type="reset" class="btn btn-default" value="Clear"/>
        <input type="submit" class="btn btn-primary" value="Save"/>

    </div>
</form>
