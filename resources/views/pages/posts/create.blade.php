@extends(config('blog.layout'))
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
@section('styles')
    <link href='{{asset('summernote/summernote-bs4.css')}}' rel='stylesheet' type='text/css'/>
@endsection

@section('scripts')
    <script src="{{asset('summernote/summernote-bs4.js')}}"></script>

    <script type="text/javascript">
        var bootstrapColors = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark'];
        var fontAwesome = [
            ['home', 'image', 'file-image-o'],
            ['book', 'bar']
        ];

        function makeDropdownToolbar(context, menuName, tag, defaultClass) {
            var ui = $.summernote.ui;

            var buttons = ui.buttonGroup([
                ui.button({
                    contents: menuName + ' <span class="fa fa-caret-down"></span>',
                    tooltip: menuName,
                    data: {
                        toggle: 'dropdown'
                    },
                    click: function () {
                        // invoke insertText method with 'hello' on editor module.
                        // context.invoke('editor.pasteHTML', ('<button class="btn btn-primary">hello</button>'));
                    }
                }),
                ui.dropdown({
                    className: 'dropdown-style',
                    items: bootstrapColors,
                    template: function (item) {

                        var className = ' class="' + defaultClass + item + '"';

                        return '<' + tag + className + '>' + item + '</' + tag + '>';
                    },

                    callback: function ($dropdown) {
                        $dropdown.find(tag).each(function () {
                            $(this).click(function () {
                                var text = $(this).text();
                                // $('<div class="' + $(this).attr('class') + '">' + text + '</div>');
                                context.invoke('editor.restoreRange');
                                context.invoke('editor.focus');
                                context.invoke("editor.pasteHTML", ('<' + tag + ' class="' + $(this).attr('class') + '"' + '>' + text + '</' + tag + '>'));
                            });
                        });
                    }
                })
            ]);

            return buttons.render();   // return button as jquery object
        }

        var AlertButton = function (context) {
            return makeDropdownToolbar(context, 'Alerts', 'div', 'alert alert-');
        };
        var textButton = function (context) {
            return makeDropdownToolbar(context, 'Texts', 'p', 'text-');
        }
        var badgeButton = function (context) {
            return makeDropdownToolbar(context, 'Badge', 'span', 'badge badge-');
        }
        var fontAwesome = function (context) {
            var ui = $.summernote.ui;

            var icons = ui.buttonGroup([
                ui.button({
                    contents:'FontAwesome <span class="fa fa-caret-down"></span>',
                    tooltip: menuName,
                    data: {
                        toggle: 'dropdown'
                    },
                    click: function () {
                        // invoke insertText method with 'hello' on editor module.
                        // context.invoke('editor.pasteHTML', ('<button class="btn btn-primary">hello</button>'));
                    }
                }),
                ui.dropdown({
                    className: 'dropdown-style',
                    items: bootstrapColors,
                    template: function (item) {

                        var className = ' class="' + defaultClass + item + '"';

                        return '<' + tag + className + '>' + item + '</' + tag + '>';
                    },

                    callback: function ($dropdown) {
                        $dropdown.find(tag).each(function () {
                            $(this).click(function () {
                                var text = $(this).text();
                                // $('<div class="' + $(this).attr('class') + '">' + text + '</div>');
                                context.invoke('editor.restoreRange');
                                context.invoke('editor.focus');
                                context.invoke("editor.pasteHTML", ('<' + tag + ' class="' + $(this).attr('class') + '"' + '>' + text + '</' + tag + '>'));
                            });
                        });
                    }
                })
            ]);

            return buttons.render();   // return button as jquery object
        }
        $(document).ready(function (e) {
            $('#summernote').summernote({
                enterHtml: '',
                height: 300,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']],
                    ['alerts', ['alert']],
                    ['texts', ['text']],
                    ['badges', ['badge']],
                    ['icon',['fontawesome']]
                ],

                buttons: {
                    alert: AlertButton,
                    text: textButton,
                    badge: badgeButton

                }
            });
        })
    </script>
@endsection