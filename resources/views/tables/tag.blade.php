<div class="row main-content">
    <div class="table-responsive">
        <table class="table table-bordered table-striped bg-light">
            <tbody>
            @foreach($records as $record)
                <tr>
                    <td>
                        <a href="{{route('blog::tags.show',$record->slug)}}"> {{$record->name }}</a>
                        <span class="badge badge-dark">{{$record->posts_count}}</span>
                    </td>
                    @if(auth()->check())
                        <td class="text-right">
                            
                            @can('update',$record)
                                <a href="{{route('blog::tags.edit',$record->slug)}}">
                                    <span class="fa fa-pencil-alt"></span>
                                </a>
                            @endcan
                            @can('delete',$record)
                                <form onsubmit="return confirm('Are you sure you want to delete?')"
                                      action="{{route('blog::tags.destroy',$record->slug)}}" method="post"
                                      style="display: inline">
                                    {{csrf_field()}}
                                    {{method_field('DELETE')}}
                                    <button type="submit" class="btn btn-default cursor-pointer  btn-sm"><i
                                                class="text-danger fa fa-times"></i></button>
                                </form>
                            @endcan
                        </td>
                    @endif
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
    </div>
</div>