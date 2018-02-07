<form action="{{$route or route('blog::categories.store')}}" method="POST">
    {{csrf_field()}}
    <input type="hidden" name="_method" value="{{$method or 'POST'}}"/>
    <div class="form-group {{ $errors->has('title') ? ' has-danger' : '' }}">
        <label for="title">Title</label>
        <input type="text" class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" id="title" value="{{old('title',$model->title)}}"
               placeholder="" maxlength="255">
        @if($errors->has('title'))
            <span class="invalid-feedback">
        <strong>{{ $errors->first('title') }}</strong>
    </span>
        @endif
    </div>
    <div class="form-group">
        <label for="parent_id">Parent </label>

        <select class="form-control {{ $errors->has('parent_id') ? ' is-invalid' : '' }}" name="parent_id" id="parent_id">
            <option value="">None</option>
            @foreach($categories as $category)
                <option value="{{$category->id}}" {{$category->id==old('parent_id',$model->parent_id)?'selected':''}}>{{$category->title}}</option>

                @if($category->children->count()>0)
                    <optgroup label="Child of {{$category->title}}">

                        @foreach($category->children as $child)
                            <option value="{{$child->id}}" {{$child->id==old('parent_id',$model->parent_id)?'selected':''}}>&nbsp;{{$child->title}}</option>

                            @if($child->children->count()>0)
                                <optgroup label="&nbsp;&nbsp;&nbsp;&nbsp; Child of {{$child->title}}">
                                    @foreach($child->children as $grandChild)
                                        <option value="{{$grandChild->id}}" {{$grandChild->id==old('parent_id',$model->parent_id)?'selected':''}}>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$grandChild->title}}</option>
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
    <div class="form-group ">
        <label for="slug">Slug</label>
        <input type="text" class="form-control {{ $errors->has('slug') ? ' is-invalid' : '' }}" name="slug" id="slug" value="{{old('slug',$model->slug)}}"
               placeholder="If blank title will be used as slug" maxlength="255">
        @if($errors->has('slug'))
            <span class="invalid-feedback ">
        <strong>{{ $errors->first('slug') }}</strong>
    </span>
        @endif
    </div>


    <div class="form-group text-right ">
        <input type="reset" class="btn btn-default" value="Clear"/>
        <input type="submit" class="btn btn-primary" value="Save"/>

    </div>
</form>