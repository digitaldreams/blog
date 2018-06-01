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
    <!-- include codemirror (codemirror.css, codemirror.js, xml.js, formatting.js)-->
    <link rel="stylesheet" type="text/css" href="http://cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/codemirror.min.css" />
    <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/theme/blackboard.min.css">
    <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/theme/monokai.min.css">

@endsection

@section('scripts')
    <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/codemirror.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/mode/xml/xml.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/codemirror/2.36.0/formatting.min.js"></script>
    <script src="{{asset('summernote/summernote-bs4.js')}}"></script>

    <script type="text/javascript">
        var bootstrapColors = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark'];
        var fontAwesomeIcons = [
            ['home', 'image', 'file-image-o'],
            ['book', 'bar']
        ];

        function makeColumns(context, menuName, defaultClass) {
            var ui = $.summernote.ui;

            var buttons = ui.buttonGroup([
                ui.button({
                    contents: menuName + ' <span class="fa fa-caret-down"></span>',
                    tooltip: menuName,
                    data: {
                        toggle: 'dropdown'
                    }
                }),
                ui.dropdown({
                    className: 'dropdown-style',
                    items: [1, 2, 3, 4, 6],
                    template: function (item) {
                        return '<div class="">' + item + '</div>';
                    },
                    callback: function ($dropdown) {
                        $dropdown.find('div').each(function () {
                            $(this).click(function () {
                                var html = '<div class="row">';
                                var colNum = parseInt($(this).text());
                                var totalCol = parseInt(12) / parseInt(colNum);
                                for (var c = 0; c < totalCol; c++) {
                                    var colSpan = parseInt(12) / parseInt(totalCol);
                                    html += '\n\n<div class="' + defaultClass + colSpan + '"' + '> ' + colSpan + ' columns </div>\n\n'
                                }
                                html += "</div>\n\n";
                                context.invoke('editor.restoreRange');
                                context.invoke('editor.focus');
                                context.invoke("editor.pasteHTML", (html));
                            });
                        });
                    }
                })
            ]);

            return buttons.render();   // return button as jquery object
        }


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
                                context.invoke("editor.insertNode", $('\n\n<' + tag + ' class="' + $(this).attr('class') + '"' + '>' + text + '</' + tag + '>\n\n')[0]);
                            });
                        });
                    }
                })
            ]);

            return buttons.render();   // return button as jquery object
        }

        var ColSm=function(context){
            return makeColumns(context,'Row sm','col-sm-')
        }

        var AlertButton = function (context) {
            return makeDropdownToolbar(context, 'Alerts', 'div', 'alert alert-');
        };
        var Buttons = function (context) {
            return makeDropdownToolbar(context, 'Button', 'button', 'btn btn-');
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
                                context.invoke("editor.pasteHTML", ('<' + tag + ' class="' + $(this).attr('class') + '"' + '></' + tag + '>\n'));
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
                    context.invoke('editor.pasteHTML', '\n\n<div class="dropdown">\n' +
                        '  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">\n' +
                        '    Dropdown button\n' +
                        '  </button>\n' +
                        '  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">\n' +
                        '    <a class="dropdown-item" href="#">Action</a>\n' +
                        '    <a class="dropdown-item" href="#">Another action</a>\n' +
                        '    <a class="dropdown-item" href="#">Something else here</a>\n' +
                        '  </div>\n' +
                        '</div>\n\n');
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
                    context.invoke('editor.pasteHTML', '\n\n<div class="jumbotron">\n' +
                        '  <h1 class="display-4">Hello, world!</h1>\n' +
                        '  <p class="lead">This is a simple hero unit, a simple jumbotron-style component for calling extra attention to featured content or information.</p>\n' +
                        '  <hr class="my-4">\n' +
                        '  <p>It uses utility classes for typography and spacing to space content out within the larger container.</p>\n' +
                        '  <p class="lead">\n' +
                        '    <a class="btn btn-primary btn-lg" href="#" role="button">Learn more</a>\n' +
                        '  </p>\n' +
                        '</div>\n\n');
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
                        '\n\n<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">\n' +
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
                        '</div>\n\n');
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
                    context.invoke('editor.pasteHTML', '\n\n<ul class="nav nav-tabs" id="myTab" role="tablist">\n' +
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
                        '</div>\n\n');
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
                    context.invoke('editor.pasteHTML', '\n\n<nav class="navbar navbar-expand-lg navbar-light bg-light">\n' +
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
                        '</nav>\n\n');
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
                    context.invoke('editor.pasteHTML', '\n\n<nav aria-label="Page navigation example">\n' +
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
                        '</nav>\n\n');
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
                    context.invoke('editor.pasteHTML', '\n\n<button type="button" class="btn btn-secondary"' +
                        ' data-container="body" ' +
                        'data-toggle="popover" data-placement="bottom" title="Popover Title"' +
                        ' data-content="Vivamus' +
                        'sagittis lacus vel augue laoreet rutrum faucibus.">\n' +
                        '  Popover on bottom\n' +
                        '</button>\n\n');
                }
            });

            return button.render();   // return button as jquery object
        }

        var Breadcrumb = function (context) {
            var ui = $.summernote.ui;

            // create button
            var button = ui.button({
                contents: 'breadcrumb',
                tooltip: 'breadcrumb',
                click: function () {
                    // invoke insertText method with 'hello' on editor module.
                    context.invoke('editor.pasteHTML', '\n\n<nav aria-label="breadcrumb">\n' +
                        '  <ol class="breadcrumb">\n' +
                        '    <li class="breadcrumb-item"><a href="#">Home</a></li>\n' +
                        '    <li class="breadcrumb-item active" aria-current="page">Library</li>\n' +
                        '  </ol>\n' +
                        '</nav>\n\n');
                }
            });

            return button.render();   // return button as jquery object
        }

        var Scrollspy = function (context) {
            var ui = $.summernote.ui;

            // create button
            var button = ui.button({
                contents: 'scrollspy',
                tooltip: 'scrollspy',
                click: function () {
                    // invoke insertText method with 'hello' on editor module.
                    context.invoke('editor.pasteHTML', '\n\n<div id="list-example" class="list-group">\n' +
                        '  <a class="list-group-item list-group-item-action" href="#list-item-1">Item 1</a>\n' +
                        '  <a class="list-group-item list-group-item-action" href="#list-item-2">Item2</a>\n' +
                        '  <a class="list-group-item list-group-item-action" href="#list-item-3">Item 3</a>\n' +
                        '  <a class="list-group-item list-group-item-action" href="#list-item-4">Item 4</a>\n' +
                        '</div>\n' +
                        '<div data-spy="scroll" data-target="#list-example" data-offset="0" class="scrollspy-example">\n' +
                        '  <h4 id="list-item-1">Item 1</h4>\n' +
                        '  <p>...</p>\n' +
                        '  <h4 id="list-item-2">Item 2</h4>\n' +
                        '  <p>...</p>\n' +
                        '  <h4 id="list-item-3">Item 3</h4>\n' +
                        '  <p>...</p>\n' +
                        '  <h4 id="list-item-4">Item 4</h4>\n' +
                        '  <p>...</p>\n' +
                        '</div>\n\n');
                }
            });

            return button.render();   // return button as jquery object
        }

        var Tooltips = function (context) {
            var ui = $.summernote.ui;

            // create button
            var button = ui.button({
                contents: 'Tooltip',
                tooltip: 'Tooltip',
                click: function () {
                    // invoke insertText method with 'hello' on editor module.
                    context.invoke('editor.pasteHTML', '<span data-toggle="tooltip" data-placement="top" title="Tooltip on top">\n' +
                        '  Tooltip on top\n' +
                        '</span>\n\n');
                }
            });

            return button.render();   // return button as jquery object
        }


        var Accordion = function (context) {
            var ui = $.summernote.ui;

            // create button
            var button = ui.button({
                contents: 'Accordion',
                tooltip: 'Accordion',
                click: function () {
                    // invoke insertText method with 'hello' on editor module.
                    context.invoke('editor.pasteHTML', '\n\n<div id="accordion">\n' +
                        '  <div class="card">\n' +
                        '    <div class="card-header" id="headingOne">\n' +
                        '      <h5 class="mb-0">\n' +
                        '        <a class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">\n' +
                        '           Collapsible Group Item #1\n' +
                        '        </a>\n' +
                        '      </h5>\n' +
                        '    </div>\n' +
                        '\n' +
                        '    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">\n' +
                        '      <div class="card-body">\n' +
                        '        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven\'t heard of them accusamus labore sustainable VHS.\n' +
                        '      </div>\n' +
                        '    </div>\n' +
                        '  </div>\n' +
                        '  <div class="card">\n' +
                        '    <div class="card-header" id="headingTwo">\n' +
                        '      <h5 class="mb-0">\n' +
                        '        <a class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">\n' +
                        '           Collapsible Group Item #2\n' +
                        '        </a>\n' +
                        '      </h5>\n' +
                        '    </div>\n' +
                        '    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">\n' +
                        '      <div class="card-body">\n' +
                        '        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven\'t heard of them accusamus labore sustainable VHS.\n' +
                        '      </div>\n' +
                        '    </div>\n' +
                        '  </div>\n' +
                        '  <div class="card">\n' +
                        '    <div class="card-header" id="headingThree">\n' +
                        '      <h5 class="mb-0">\n' +
                        '        <a class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">\n' +
                        '           Collapsible Group Item #3\n' +
                        '        </a>\n' +
                        '      </h5>\n' +
                        '    </div>\n' +
                        '    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">\n' +
                        '      <div class="card-body">\n' +
                        '        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven\'t heard of them accusamus labore sustainable VHS.\n' +
                        '      </div>\n' +
                        '    </div>\n' +
                        '  </div>\n' +
                        '</div>');
                }
            });

            return button.render();   // return button as jquery object
        }

        var Carousel = function (context) {
            var ui = $.summernote.ui;

            // create button
            var button = ui.button({
                contents: 'carousel',
                tooltip: 'carousel',
                click: function () {
                    // invoke insertText method with 'hello' on editor module.
                    context.invoke('editor.pasteHTML', '\n\n<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">\n' +
                        '  <ol class="carousel-indicators">\n' +
                        '    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>\n' +
                        '    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>\n' +
                        '    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>\n' +
                        '  </ol>\n' +
                        '  <div class="carousel-inner">\n' +
                        '    <div class="carousel-item active">\n' +
                        '      <img class="d-block w-100" src="https://cdn.pixabay.com/photo/2015/04/19/08/32/marguerite-729510_960_720.jpg" alt="First slide">\n' +
                        '     <div class="carousel-caption d-none d-md-block">\n' +
                        '    <h5>Title</h5>\n' +
                        '    <p>Description</p>\n' +
                        '  </div></div>\n' +
                        '    <div class="carousel-item">\n' +
                        '      <img class="d-block w-100" src="https://www.alltechbuzz.net/wp-content/uploads/2017/05/15-Best-And-Beautiful-Weather-Widgets-For-Your-Android-Home-Screens..png" alt="Second slide">\n' +
                        '     <div class="carousel-caption d-none d-md-block">\n' +
                        '    <h5>...</h5>\n' +
                        '    <p>...</p>\n' +
                        '  </div></div>\n' +
                        '    <div class="carousel-item">\n' +
                        '      <img class="d-block w-100" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS7XnlSbGwy-e4FDstNwjEzcfPBPqtnq2Ju-lmH4DrZvIw1uZiLKA" alt="Third slide">\n' +
                        '     <div class="carousel-caption d-none d-md-block">\n' +
                        '    <h5>...</h5>\n' +
                        '    <p>...</p>\n' +
                        '  </div></div>\n' +
                        '  </div>\n' +
                        '  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">\n' +
                        '    <span class="carousel-control-prev-icon" aria-hidden="true"></span>\n' +
                        '    <span class="sr-only">Previous</span>\n' +
                        '  </a>\n' +
                        '  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">\n' +
                        '    <span class="carousel-control-next-icon" aria-hidden="true"></span>\n' +
                        '    <span class="sr-only">Next</span>\n' +
                        '  </a>\n' +
                        '</div>\n\n');
                }
            });

            return button.render();   // return button as jquery object
        }


        var Blockquote = function (context) {
            var ui = $.summernote.ui;

            // create button
            var button = ui.button({
                contents: 'blockquote',
                tooltip: 'blockquote',
                click: function () {
                    // invoke insertText method with 'hello' on editor module.
                    context.invoke('editor.pasteHTML', '\n\n<blockquote class="blockquote text-right">\n' +
                        '  <p class="mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>\n' +
                        '  <footer class="blockquote-footer">Someone famous in <cite title="Source Title">Source Title</cite></footer>\n' +
                        '</blockquote>\n\n');
                }
            });

            return button.render();   // return button as jquery object
        }
        var Figure = function (context) {
            var ui = $.summernote.ui;

            // create button
            var button = ui.button({
                contents: 'figure',
                tooltip: 'figure',
                click: function () {
                    context.invoke('editor.pasteHTML', '\n\n<figure class="figure">\n' +
                        '  <img src="http://marcroftmedical.com/wp-content/themes/marcroft/images/default-blog.jpg" class="figure-img img-fluid rounded" alt="A generic square placeholder image with rounded corners in a figure.">\n' +
                        '  <figcaption class="figure-caption text-center">A caption for the above image.</figcaption>\n' +
                        '</figure>\n\n');
                }
            });

            return button.render();   // return button as jquery object
        }

        $(document).ready(function (e) {
            $('#summernote').summernote({
                tabsize: 2,
                enterHtml:"<br/>",
                codemirror: {
                    htmlMode: true,
                    theme: 'monokai',
                    mode: "text/html",
                    lineNumbers: true,
                    tabMode: 'indent'
                },
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
                    ['buttons', ['button']],
                    ['texts', ['text']],
                    ['badges', ['badge']],
                    ['icons', ['fontawesome']],
                    ['dropdown', ['dropdown', 'jb', 'listGroup']],
                    ['modal', ['modal']],
                    ['tab', ['tab']],
                    ['navbar', ['navbar']],
                    ['pagination', ['pagination']],
                    ['popover', ['popover']],
                    ['scrollspy', ['scrollspy']],
                    ['tooltip', ['tooltip']],
                    ['accordion', ['accordion']],
                    ['carousel', ['carousel']],
                    ['breadcrumb', ['breadcrumb']],
                    ['blockquote', ['blockquote']],
                    ['figure', ['figure']],
                    ['rowsm', ['rowsm']],
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
                    popover: Popover,
                    scrollspy: Scrollspy,
                    tooltip: Tooltips,
                    accordion: Accordion,
                    carousel: Carousel,
                    button: Buttons,
                    breadcrumb: Breadcrumb,
                    blockquote: Blockquote,
                    figure: Figure,
                    rowsm: ColSm,
                }
            });
        })
    </script>
@endsection