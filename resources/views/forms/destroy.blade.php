<form style="display: inline" class="card-link" onsubmit="return confirm('Are you sure you want to delete?')"
      action="{{$route ?? ''}}"
      method="post" style="display: inline">
    {{csrf_field()}}
    {{method_field('DELETE')}}
    <button type="submit" class="btn btn-light cursor-pointer  btn-sm">
        <i class="text-danger fa fa-times"></i></button>
</form>