var colNumbers = [1, 2, 3, 4, 6];

function makeColumnDropdown(context, menuName, tooltip, rootClass) {
    // ui has renders to build ui elements.
    //  - you can create a button with `ui.button`
    var ui = $.summernote.ui;


    context.memo('button.' + menuName, function () {
        return ui.buttonGroup([
            ui.button({
                className: 'dropdown-toggle',
                contents: menuName,
                tooltip: tooltip,
                data: {
                    toggle: 'dropdown'
                }
            }),
            ui.dropdown({
                className: 'dropdown-style scrollable-menu',
                items: colNumbers,
                template: function (item) {
                    return parseInt(item);
                },
                click: function (event, namespace, value) {

                    event.preventDefault();
                    var html = '\n\n<div class="row">';
                    var colNum = value || $(event.target).text();
                    for (var c = 0; c < parseInt(colNum); c++) {
                        var colSpan = parseInt(12) / parseInt(colNum);
                        var currentColNumber = parseInt(c) + parseInt(1);
                        html += '\n\n<div class="col-' + rootClass + colSpan + '"' + '> ' + currentColNumber + '  </div>\n\n'
                    }
                    html += "</div>\n\n";
                    context.invoke('editor.restoreRange');
                    context.invoke('editor.focus');
                    pasteHtmlAtCaret(html)
                }
            })
        ]).render();
    });
}

(function (factory) {
    /* global define */
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as an anonymous module.
        define(['jquery'], factory);
    } else if (typeof module === 'object' && module.exports) {
        // Node/CommonJS
        module.exports = factory(require('jquery'));
    } else {
        // Browser globals
        factory(window.jQuery);
    }
}(function ($) {

    // Extends plugins for adding hello.
    //  - plugin is external module for customizing.
    $.extend($.summernote.plugins, {
        /**
         * @param {Object} context - context object has status of editor.
         */
        'colsm': function (context) {
            return makeColumnDropdown(context, 'colsm', 'Column for Tablet', 'sm-')
        },
        'colmd': function (context) {
            return makeColumnDropdown(context, 'colmd', 'Medium device column', 'md-');
        },
        'collg': function (context) {
            return makeColumnDropdown(context, 'collg', 'Large monitor column. Greater than 1200px', 'lg-');
        },
        'colxs': function (context) {
            return makeColumnDropdown(context, 'colxs', 'Mobile device column', '');
        }

    });
}));
