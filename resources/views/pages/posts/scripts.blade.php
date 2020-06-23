@section('script')
    @include('blog::includes.popoverModal')
    @include('blog::includes.tooltipModal')
    @include('blog::includes.summernoteImageInsertModal')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.textcomplete/1.8.4/jquery.textcomplete.min.js"></script>
    <script type="text/javascript" src="{{asset('js/bootsum.js')}}"></script>
    <script type="text/javascript">

        $('#blog_tags').select2({
            minimumInputLength:2,
            ajax: {
                url: '{{route('blog::tags.select2')}}',
                dataType: 'json'
            }
        });

        $('#category_id').select2({
            minimumInputLength:2,
            ajax: {
                url: '{{route('blog::categories.select2')}}',
                dataType: 'json'
            }
        });
    </script>
@endsection
