<form action="{{isset($route)?$route:route('blog::newsletters.store')}}" method="POST" class="m-form m-form--fit">
    {{csrf_field()}}
    <input type="hidden" name="_method" value="{{isset($method)?$method:'POST'}}"/>
    <div class="mb-3">
        <label for="name" class="col-form-label">Name</label>
        <input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" id="name"
               value="{{old('name',$model->name)}}" placeholder="" maxlength="255">
        @if($errors->has('name'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('name') }}</strong>
            </div>
        @endif
    </div>

    <div class="mb-3">
        <label for="email" class="col-form-label">Email</label>
        <input type="text" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" id="email"
               value="{{old('email',$model->email)}}" placeholder="" maxlength="255">
        @if($errors->has('email'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('email') }}</strong>
            </div>
        @endif
    </div>

    <div class="mb-3">
        <label for="status" class="col-form-label">Status</label>
        <select class="form-select {{ $errors->has('status') ? ' is-invalid' : '' }}" name="status" id="status">
            <option value="subscribed" {{old('status',$model->status)=='subscribed'?"selected":""}} >Subscribed</option>
            <option value="unsubscribed" {{old('status',$model->status)=='unsubscribed'?"selected":""}} >Unsubscribed
            </option>

        </select>
        @if($errors->has('status'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('status') }}</strong>
            </div>
        @endif
    </div>


    <div class="text-right ">
        <input type="reset" class="btn btn-default" value="Clear"/>
        <input type="submit" class="btn btn-primary" value="Save"/>

    </div>
</form>
