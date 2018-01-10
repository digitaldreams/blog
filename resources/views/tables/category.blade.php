<table class="table table-bordered table-striped">
    <tbody>
    @foreach($records as $record)
        <tr>
            <td>
                <a href="{{route('blog::categories.show',$record->slug)}}"> {{$record->title }}</a>
                <span class="badge badge-dark">{{$record->posts_count}}</span>
            </td>
            <td>
                @if(auth()->check())
                    <a href="{{route('blog::categories.show',$record->slug)}}">
                        <span class="fa fa-eye"></span>
                    </a><a href="{{route('blog::categories.edit',$record->slug)}}">
                        <span class="fa fa-pencil"></span>
                    </a>
                    <form onsubmit="return confirm('Are you sure you want to delete?')"
                          action="{{route('blog::categories.destroy',$record->slug)}}" method="post" style="display: inline">
                        {{csrf_field()}}
                        {{method_field('DELETE')}}
                        <button type="submit" class="btn btn-default cursor-pointer  btn-sm"><i
                                    class="text-danger fa fa-remove"></i></button>
                    </form>
                @endif
            </td>
        </tr>

    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td colspan="3">
            {{{$records->render()}}}
        </td>
    </tr>
    </tfoot>
</table>