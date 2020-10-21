@section('script')
    @include('blog::includes.popoverModal')
    @include('blog::includes.tooltipModal')
    @include('blog::includes.summernoteImageInsertModal')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.textcomplete/1.8.4/jquery.textcomplete.min.js"></script>
    <script type="text/javascript" src="{{asset('js/bootsum.js')}}"></script>
    <script type="text/javascript">

        $('#blog_tags').select2({
            placeholder: 'Select one or more tags or create new',
            tags: true,
            tokenSeparators: [",",],
            createSearchChoice: function (term, data) {
                if ($(data).filter(function () {
                    return this.text.localeCompare(term) === 0;
                }).length === 0) {
                    return {
                        id: term,
                        text: term
                    };
                }
            },
            ajax: {
                url: '{{route('blog::tags.select2')}}',
                dataType: 'json'
            }
        });

        $('#category_id').select2({
            placeholder: 'Select a category from below',
            ajax: {
                url: '{{route('blog::categories.select2')}}',
                dataType: 'json'
            }
        });
    </script>
@endsection
