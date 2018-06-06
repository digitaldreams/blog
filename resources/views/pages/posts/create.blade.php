@extends(config('blog.layout.create'))
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('blog::posts.index')}}">
            <span class=""> posts</span>
        </a>
    </li>
    <li class="breadcrumb-item">
        Create
    </li>
@endsection
@section('content')
    <div class="row">
        <div class='col-md-12'>
            <div class='panel panel-default'>
                <div class="panel-body">
                    @include('blog::forms.post',['categories'=>$categories])
                </div>
            </div>
        </div>
    </div>
@endSection
@section('topright')
    @include('blog::pages.posts.top_right')
@endsection
@section('scripts')
    <script type="text/javascript">
        $('#blog_tags').select2();
    </script>
@endsection