function addMenu(context, menuName, title, content) {
    var ui = $.summernote.ui;
    // create button
    var formattedName = title.replace(/([A-Z])/g, ' $1')
        .replace(/^./, function (str) {
            return str.toUpperCase();
        })
    formattedName = formattedName.replace("_", " ");
    context.memo('button.' + menuName, function () {
        return ui.button({
            contents: formattedName,
            tooltip: formattedName,
            click: function (event, namespace, value) {
                event.preventDefault();
                var $node = $(context.invoke("restoreTarget"))
                if ($node.length == 0) {
                    $node = $(document.getSelection().focusNode.parentElement, ".note-editable");
                }

                context.invoke('editor.restoreRange');
                context.invoke('editor.focus');

                pasteHtmlAtCaret(content);
            }
        }).render()
    })
}

function addDropdown(context, menuName, title, list, content) {
    var ui = $.summernote.ui;
    var formattedmenuName = title;
    context.memo('button.' + menuName, function () {
        return ui.buttonGroup([
            ui.button({
                contents: formattedmenuName + ' <span class="fa fa-caret-down"></span>',
                tooltip: formattedmenuName,
                data: {
                    toggle: 'dropdown'
                }
            }),
            ui.dropdown({
                className: 'dropdown-style',
                items: list,
                template: function (item) {
                    var formattedName = item.replace(/([A-Z])/g, ' $1')
                        .replace(/^./, function (str) {
                            return str.toUpperCase();
                        })
                    formattedName = formattedName.replace(/_/g, " ");
                    return '<div class="" data-key="' + menuName + '/' + item + '">' + formattedName + '</div>';
                },
                callback: function ($dropdown) {
                    $dropdown.find('div').each(function () {
                        $(this).click(function () {
                            var path = $(this).data('key');
                            if (path.indexOf(".") !== -1) {
                                var pathArr = path.split(".");
                                pathArr.shift();
                                path = pathArr.pop();
                            }
                            var html = faker.fake(content[path])
                            context.invoke('editor.restoreRange');
                            context.invoke('editor.focus');

                            var $node = $(context.invoke("restoreTarget"))
                            if ($node.length == 0) {
                                $node = $(document.getSelection().focusNode.parentElement, ".note-editable");
                            }
                            pasteHtmlAtCaret(html);

                        });
                    });
                }
            })
        ]).render();
    })

}


