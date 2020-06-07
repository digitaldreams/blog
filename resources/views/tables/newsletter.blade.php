<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    @foreach($records as $record)
        <tr>
            <td> {{$record->name }} </td>
            <td>
                <a class=""
                   href="{{route('blog::newsletters.show',$record->id)}}"> {{$record->email }}  </a>
            </td>
            <td> {{$record->status }} </td>

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