<form style="display: inline" class="card-link" onsubmit="return confirm('Are you sure you want to delete?')"
      action="{{$route or ''}}"
      method="post" style="display: inline">
    {{csrf_field()}}
    {{method_field('DELETE')}}
    <button type="submit" class="btn btn-light cursor-pointer  btn-sm">
        <i class="far fa-trash-alt"></i>
        </button> 
</form>