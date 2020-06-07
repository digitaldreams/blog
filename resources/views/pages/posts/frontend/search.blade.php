<script type="text/javascript" src="{{asset('js/jquery-ui.min.js')}}"></script>
<script type="text/javascript">
    (function ($) {

        $("#search-area").autocomplete({
            minLength: 3,
            source: function (request, response) {
                var type = $("#looking").val();
                var state = $("#state").val();
                $.get('/posts/smart-search?search=' + request.term).then(function (rsp) {
                    response(rsp.data);
                });
            },
            select: function (event, ui) {
                $("#search-area").val(ui.item.title);
                $("#looking").val(ui.item.type);
                return window.location.href = ui.item.link;
            },
            focus: function (event, ui) {
                $("#search-area").val(ui.item.title);
                return false;
            }
        }).autocomplete("instance")._renderItem = function (ul, item) {
            return $("<li>")
                .append("<div><b>" + item.title + "</b></div>")
                .appendTo(ul);
        };
    })(jQuery)
</script>