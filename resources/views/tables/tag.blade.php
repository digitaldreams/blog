<table class="table table-bordered table-striped bg-light">
    <tbody>
    @foreach($records as $record)
        <tr>
            <td>
                <a href="{{route('blog::tags.show',$record->slug)}}"> {{$record->name }}</a>
                <span class="badge badge-dark">{{$record->posts_count}}</span>
            </td>
            <td class="text-right">
                @if(auth()->check())
                    <a href="{{route('blog::tags.show',$record->slug)}}">
                        <span class="fa fa-eye"></span>
                    </a>&nbsp;&nbsp;
                    <a href="{{route('blog::tags.edit',$record->slug)}}">
                        <span class="fa fa-pencil"></span>
                    </a>
                    &nbsp;&nbsp;
                    <form onsubmit="return confirm('Are you sure you want to delete?')"
                          action="{{route('blog::tags.destroy',$record->slug)}}" method="post"
                          style="display: inline">
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