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
        var fontAwesomeIcons = [
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
            var defaultClass = 'fa fa-';
            var tag = 'i'
            var icons = ui.buttonGroup([
                ui.button({
                    contents: 'Icons <span class="fa fa-caret-down"></span>',
                    tooltip: 'Icons',
                    data: {
                        toggle: 'dropdown'
                    }
                }),
                ui.dropdown({
                    className: 'dropdown-style',
                    items: fontAwesomeIcons,
                    template: function (item) {
                        var html = '';
                        if (Array.isArray(item)) {
                            for (var c = 0; c < item.length; c++) {
                                var className = ' class="' + defaultClass + item[c] + '"';
                                html += '<' + tag + className + ' data-toggle="tooltip" title="' + item[c] + '"></' + tag + '>&nbsp;&nbsp;';
                            }
                        } else {
                            var className = ' class="' + defaultClass + item + '"';
                            html = '<' + tag + className + '>' + item + '</' + tag + '>';
                        }
                        return html;
                    },

                    callback: function ($dropdown) {
                        $dropdown.find(tag).each(function () {
                            $(this).click(function () {
                                context.invoke('editor.restoreRange');
                                context.invoke('editor.focus');
                                context.invoke("editor.pasteHTML", ('<' + tag + ' class="' + $(this).attr('class') + '"' + '></' + tag + '>'));
                            });
                        });
                    }
                })
            ]);

            return icons.render();   // return button as jquery object
        }

        var Dropdown = function (context) {
            var ui = $.summernote.ui;

            // create button
            var button = ui.button({
                contents: 'Dropdown',
                tooltip: 'Dropdown button',
                click: function () {
                    // invoke insertText method with 'hello' on editor module.
                    context.invoke('editor.pasteHTML', '<div class="dropdown">\n' +
                        '  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">\n' +
                        '    Dropdown button\n' +
                        '  </button>\n' +
                        '  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">\n' +
                        '    <a class="dropdown-item" href="#">Action</a>\n' +
                        '    <a class="dropdown-item" href="#">Another action</a>\n' +
                        '    <a class="dropdown-item" href="#">Something else here</a>\n' +
                        '  </div>\n' +
                        '</div>');
                }
            });

            return button.render();   // return button as jquery object
        }
        var Jumbotron = function (context) {
            var ui = $.summernote.ui;
            var button = ui.button({
                contents: 'JB',
                tooltip: 'Jumbotron',
                click: function () {
                    // invoke insertText method with 'hello' on editor module.
                    context.invoke('editor.pasteHTML', '<div class="jumbotron">\n' +
                        '  <h1 class="display-4">Hello, world!</h1>\n' +
                        '  <p class="lead">This is a simple hero unit, a simple jumbotron-style component for calling extra attention to featured content or information.</p>\n' +
                        '  <hr class="my-4">\n' +
                        '  <p>It uses utility classes for typography and spacing to space content out within the larger container.</p>\n' +
                        '  <p class="lead">\n' +
                        '    <a class="btn btn-primary btn-lg" href="#" role="button">Learn more</a>\n' +
                        '  </p>\n' +
                        '</div>');
                }
            });

            return button.render();   // return button as jquery object
        }
        var ListGroup = function (context) {
            var ui = $.summernote.ui;

            // create button
            var button = ui.button({
                contents: 'LG',
                tooltip: 'List Group',
                click: function () {
                    // invoke insertText method with 'hello' on editor module.
                    context.invoke('editor.pasteHTML', '<ul class="list-group">\n' +
                        '  <li class="list-group-item">Cras justo odio</li>\n' +
                        '  <li class="list-group-item">Dapibus ac facilisis in</li>\n' +
                        '  <li class="list-group-item">Morbi leo risus</li>\n' +
                        '  <li class="list-group-item">Porta ac consectetur ac</li>\n' +
                        '  <li class="list-group-item">Vestibulum at eros</li>\n' +
                        '</ul>');
                }
            });

            return button.render();   // return button as jquery object
        }
        var Modal = function (context) {
            var ui = $.summernote.ui;

            // create button
            var button = ui.button({
                contents: 'Modal',
                tooltip: 'Modal',
                click: function () {
                    // invoke insertText method with 'hello' on editor module.
                    context.invoke('editor.pasteHTML', '<!-- Button trigger modal -->\n' +
                        '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">\n' +
                        '  Launch demo modal\n' +
                        '</button>\n' +
                        '\n' +
                        '<!-- Modal -->\n' +
                        '<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">\n' +
                        '  <div class="modal-dialog" role="document">\n' +
                        '    <div class="modal-content">\n' +
                        '      <div class="modal-header">\n' +
                        '        <h5 class="modal-title" id="exampleModalLabel"> Modal title</h5>\n' +
                        '        <button type="button" class="close" data-dismiss="modal" aria-label="Close">\n' +
                        '          <span aria-hidden="true">&times;</span>\n' +
                        '        </button>\n' +
                        '      </div>\n' +
                        '      <div class="modal-body">\n' +
                        '        Text\n' +
                        '      </div>\n' +
                        '      <div class="modal-footer">\n' +
                        '        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>\n' +
                        '        <button type="button" class="btn btn-primary">Save changes</button>\n' +
                        '      </div>\n' +
                        '    </div>\n' +
                        '  </div>\n' +
                        '</div>');
                }
            });

            return button.render();   // return button as jquery object
        }


        var Tab = function (context) {
            var ui = $.summernote.ui;

            // create button
            var button = ui.button({
                contents: 'Tabs',
                tooltip: 'Tabs',
                click: function () {
                    // invoke insertText method with 'hello' on editor module.
                    context.invoke('editor.pasteHTML', '<ul class="nav nav-tabs" id="myTab" role="tablist">\n' +
                        '  <li class="nav-item">\n' +
                        '    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"> Home</a>\n' +
                        '  </li>\n' +
                        '  <li class="nav-item">\n' +
                        '    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"> Profile</a>\n' +
                        '  </li>\n' +
                        '  <li class="nav-item">\n' +
                        '    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false"> Contact</a>\n' +
                        '  </li>\n' +
                        '</ul>\n' +
                        '<div class="tab-content" id="myTabContent">\n' +
                        '  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">...</div>\n' +
                        '  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">...</div>\n' +
                        '  <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>\n' +
                        '</div>');
                }
            });

            return button.render();   // return button as jquery object
        }

        var NavBar = function (context) {
            var ui = $.summernote.ui;

            // create button
            var button = ui.button({
                contents: 'Navbar',
                tooltip: 'Nav Bar',
                click: function () {
                    // invoke insertText method with 'hello' on editor module.
                    context.invoke('editor.pasteHTML', '<nav class="navbar navbar-expand-lg navbar-light bg-light">\n' +
                        '  <a class="navbar-brand" href="#">Navbar</a>\n' +
                        '  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">\n' +
                        '    <span class="navbar-toggler-icon"></span>\n' +
                        '  </button>\n' +
                        '  <div class="collapse navbar-collapse" id="navbarNavDropdown">\n' +
                        '    <ul class="navbar-nav">\n' +
                        '      <li class="nav-item active">\n' +
                        '        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>\n' +
                        '      </li>\n' +
                        '      <li class="nav-item">\n' +
                        '        <a class="nav-link" href="#">Features</a>\n' +
                        '      </li>\n' +
                        '      <li class="nav-item">\n' +
                        '        <a class="nav-link" href="#">Pricing</a>\n' +
                        '      </li>\n' +
                        '      <li class="nav-item dropdown">\n' +
                        '        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">\n' +
                        '          Dropdown link\n' +
                        '        </a>\n' +
                        '        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">\n' +
                        '          <a class="dropdown-item" href="#">Action</a>\n' +
                        '          <a class="dropdown-item" href="#">Another action</a>\n' +
                        '          <a class="dropdown-item" href="#">Something else here</a>\n' +
                        '        </div>\n' +
                        '      </li>\n' +
                        '    </ul>\n' +
                        '  </div>\n' +
                        '</nav>');
                }
            });

            return button.render();   // return button as jquery object
        }


        var Pagination = function (context) {
            var ui = $.summernote.ui;

            // create button
            var button = ui.button({
                contents: 'Pagination',
                tooltip: 'Pagination',
                click: function () {
                    // invoke insertText method with 'hello' on editor module.
                    context.invoke('editor.pasteHTML', '<nav aria-label="Page navigation example">\n' +
                        '  <ul class="pagination">\n' +
                        '    <li class="page-item">\n' +
                        '      <a class="page-link" href="#" aria-label="Previous">\n' +
                        '        <span aria-hidden="true">&laquo;</span>\n' +
                        '        <span class="sr-only">Previous</span>\n' +
                        '      </a>\n' +
                        '    </li>\n' +
                        '    <li class="page-item"><a class="page-link" href="#">1</a></li>\n' +
                        '    <li class="page-item"><a class="page-link" href="#">2</a></li>\n' +
                        '    <li class="page-item"><a class="page-link" href="#">3</a></li>\n' +
                        '    <li class="page-item">\n' +
                        '      <a class="page-link" href="#" aria-label="Next">\n' +
                        '        <span aria-hidden="true">&raquo;</span>\n' +
                        '        <span class="sr-only">Next</span>\n' +
                        '      </a>\n' +
                        '    </li>\n' +
                        '  </ul>\n' +
                        '</nav>');
                }
            });

            return button.render();   // return button as jquery object
        }

        var Popover = function (context) {
            var ui = $.summernote.ui;

            // create button
            var button = ui.button({
                contents: 'Popover',
                tooltip: 'Popover',
                click: function () {
                    // invoke insertText method with 'hello' on editor module.
                    context.invoke('editor.pasteHTML', '<button type="button" class="btn btn-secondary"' +
                        ' data-container="body" ' +
                        'data-toggle="popover" data-placement="bottom" title="Popover Title"' +
                        ' data-content="Vivamus\n' +
                        'sagittis lacus vel augue laoreet rutrum faucibus.">\n' +
                        '  Popover on bottom\n' +
                        '</button>');
                }
            });

            return button.render();   // return button as jquery object
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
                    ['icons', ['fontawesome']],
                    ['dropdown', ['dropdown', 'jb', 'listGroup']],
                    ['modal', ['modal']],
                    ['tab', ['tab']],
                    ['navbar', ['navbar']],
                    ['pagination', ['pagination']],
                    ['popover',['popover']]
                ],

                buttons: {
                    alert: AlertButton,
                    text: textButton,
                    badge: badgeButton,
                    fontawesome: fontAwesome,
                    dropdown: Dropdown,
                    jb: Jumbotron,
                    listGroup: ListGroup,
                    modal: Modal,
                    tab: Tab,
                    navbar: NavBar,
                    pagination: Pagination,
                    popover: Popover

                }
            });
        })
    </script>
@endsection