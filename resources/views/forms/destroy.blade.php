<form class="card-link" onsubmit="return confirm('Are you sure you want to delete?')" action="{{$route ?? ''}}" method="post">
    {{csrf_field()}}
    {{method_field('DELETE')}}
    <button type="submit">
        <i class="mdi mdi-delete"></i>
        <div class="text">Delete</div>
    </button>
</form>