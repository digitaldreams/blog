<div class="dropdown dropleft" id="dropdown-{{$record->id}}">
    <a href="#" class="fa fa-ellipsis-v" data-toggle="dropdown" role="button"
       aria-expanded="false">
    </a>
    <ul class="dropdown-menu list-group-flush">
        <li class="list-group-item">
            <a class="card-link"
               href="{{route('blog::posts.edit',$record->slug)}}">
                <span class="fa fa-pencil-alt"></span> Edit
            </a>
        </li>
        <li class="list-group-item">
            @include('blog::forms.destroy',['route'=>route('blog::posts.destroy',$record->slug)])
            Destroy
        </li>
        @if(is_object($record->image))
            @can('update',$record->image)
                <li class="list-group-item">
                    <a class="card-link"
                       href="{{route('photo::photos.edit',$record->image_id)}}?returnUrl={{request()->fullUrl()}}">
                        <span class="fa fa-pencil-alt"> Edit Image</span>
                    </a>
                </li>
                <li class="list-group-item">
                    <a class="card-link"
                       href="{{route('photo::photos.show',$record->image_id)}}?returnUrl={{request()->fullUrl()}}">
                        <span class="fa fa-crop"> Crop Image</span>
                    </a>
                </li>
            @endcan
        @endif
    </ul>
</div>
