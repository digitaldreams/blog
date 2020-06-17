var HTML5TagList = {
    'sementic': ['main', 'section', 'article', 'header', 'footer', 'aside', 'nav'],
    'text': ['small', 'p', 'mark', 'strike', 'span', 'sub', 'sup', 'code', 'strong', 'blockquote'],
    'others': ['time', 'datalist', 'dl', 'dt', 'dd', 'abbr', 'address'],
};

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
        'tags': function (context) {

            var self = this;
            // ui has renders to build ui elements.
            //  - you can create a button with `ui.button`
            var ui = $.summernote.ui;
            if (typeof context.options.tags === 'undefined') {
                context.options.tags = {};
            }
            if (typeof context.options.tags.list === 'undefined') {
                context.options.tags.list = HTML5TagList;
            }
            context.memo('button.tags', function () {
                return ui.buttonGroup([
                    ui.button({
                        className: 'dropdown-toggle',
                        contents: 'tags',
                        tooltip: 'Html 5 tags',
                        data: {
                            toggle: 'dropdown'
                        }
                    }),
                    ui.dropdown({
                        className: 'dropdown-style',
                        items: Object.keys(context.options.tags.list),
                        template: function (item) {
                            var key = item;
                            var itemList = context.options.tags.list[item];
                            if (item.length >= 2) {
                                var html = '<p class="bg-light m-0"><u>' + item + '</u>  &nbsp;';

                            } else {
                                var html = '<p class="bg-light m-0 p-0">  &nbsp;';
                            }

                            if (Array.isArray(itemList)) {
                                for (var c = 0; c < itemList.length; c++) {
                                    var className = ' class="badge badge-secondary"';
                                    html += '<label' + className + ' data-toggle="tooltip" title="' + itemList[c] + '">' + itemList[c] + '</label>&nbsp;&nbsp;';
                                }
                            } else {
                                var className = ' class="badge badge-secondary"';
                                html = '<label' + className + '>' + itemList + '</label>';
                            }
                            html += '</p>'
                            return html;
                        },
                        click: function (event, namespace, value) {

                            event.preventDefault();
                            context.invoke('editor.restoreRange');
                            context.invoke('editor.focus');

                            if (event.target.nodeName == 'LABEL') {

                                var tagName = event.target.innerText;

                                if (window.getSelection) {

                                    var sel = window.getSelection();

                                    if (sel.focusNode.nodeType == 3) {
                                        if (sel.rangeCount) {
                                            var span = document.createElement(tagName);

                                            var range = sel.getRangeAt(0).cloneRange();
                                            var existingContent = range.toString();

                                            if (existingContent.length > 0) {
                                                try {
                                                    range.surroundContents(span);
                                                    sel.removeAllRanges();
                                                    sel.addRange(range);
                                                } catch (e) {
                                                    console.log(e);
                                                }
                                            } else {
                                                pasteHtmlAtCaret('<' + tagName + '>' + tagName + '</' + tagName + '>\n');
                                            }
                                        }
                                    } else {
                                        pasteHtmlAtCaret('<' + tagName + '>' + tagName + '</' + tagName + '>\n');
                                    }

                                }


                                //
                            }
                        }
                    })
                ]).render();
            });
        }

    })
    ;
}))
;
