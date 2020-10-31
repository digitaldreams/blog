<div class="dropdown dropleft" id="dropdown-{{$record->id}}">
    <a href="#" data-toggle="dropdown" role="button"
       aria-expanded="false"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-three-dots-vertical" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
        </svg>
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
