function toggleNodeClass(context, menuName, list, tag) {
    var self = this;
    // ui has renders to build ui elements.
    //  - you can create a button with `ui.button`
    var ui = $.summernote.ui;

    context.memo('button.' + menuName, function () {
        return ui.buttonGroup([
            ui.button({
                className: 'dropdown-toggle',
                contents: menuName,
                tooltip: menuName,
                data: {
                    toggle: 'dropdown'
                }
            }),
            ui.dropdown({
                className: 'dropdown-style',
                items: list,
                template: function (item) {

                    var style = ' style="margin:0px"';
                    var className = ' class="' + item + '"';
                    return '<' + tag + style + className + '>' + item + '</' + tag + '>';
                },
                click: function (event, namespace, value) {

                    event.preventDefault();
                    value = value || $(event.target).closest('[data-value]').data('value');

                    var $node = $(context.invoke("restoreTarget"))
                    if ($node.length == 0) {
                        $node = $(document.getSelection().focusNode.parentElement, ".note-editable");
                    }
                    $node.toggleClass(value)
                }
            })
        ]).render();
    });
};

function applyClassToSelectedText(context, menuName, list, tag) {
    var ui = $.summernote.ui;

    context.memo('button.' + menuName, function () {
        return ui.buttonGroup([
            ui.button({
                className: 'dropdown-toggle',
                contents: menuName,
                tooltip: menuName,
                data: {
                    toggle: 'dropdown'
                }
            }),
            ui.dropdown({
                className: 'dropdown-style',
                items: list,
                template: function (item) {
                    var style = ' style="margin:0px"';
                    var className = ' class="' + item + '"';
                    return '<' + tag + style + className + '>' + item + '</' + tag + '>';
                },
                click: function (event, namespace, value) {

                    event.preventDefault();
                    if (window.getSelection) {
                        value = value || $(event.target).closest('[data-value]').data('value');

                        var sel = window.getSelection();

                        if (sel.focusNode.nodeType == 3) {
                            if (sel.rangeCount) {
                                var span = document.createElement(tag);
                                var clsList = value.split(" ");
                                span.classList.add(...clsList);

                                var range = sel.getRangeAt(0).cloneRange();
                                var existingContent = range.toString();

                                if (existingContent.length > 0) {
                                    try {
                                        range.surroundContents(span);
                                        sel.removeAllRanges();
                                        sel.addRange(range);
                                    } catch (e) {
                                        window.getSelection().baseNode.parentNode.classList.add(value);
                                    }
                                } else {
                                    var btnHtml = '<'+tag+ ' class="' + value + '">'+menuName+'</'+tag+'>';
                                    pasteHtmlAtCaret(btnHtml);
                                }
                            }
                        } else {
                        }

                    }
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
        'alerts': function (context) {
            var alertClasses = [];
            for (var i = 0; i < BootstrapClasses.alert.length; i++) {
                alertClasses[i] = "alert " + BootstrapClasses.alert[i];
            }
            return toggleNodeClass(context, 'alerts', alertClasses, 'div')
        },
        'texts': function (context) {
            return applyClassToSelectedText(context, 'texts', BootstrapClasses.text, 'span')
        },
        'inputButtons': function (context) {
            var buttonClasses = [];
            for (var i = 0; i < BootstrapClasses.button.length; i++) {
                buttonClasses[i] = "btn " + BootstrapClasses.button[i];
            }
            return applyClassToSelectedText(context, 'inputButtons', buttonClasses, 'button')
        },
        'badges': function (context) {
            var badgeClasses = [];
            for (var i = 0; i < BootstrapClasses.badge.length; i++) {
                badgeClasses[i] = "badge " + BootstrapClasses.badge[i];
            }
            return applyClassToSelectedText(context, 'badges', badgeClasses, 'span');
        },
        'bg-color': function (context) {
            return toggleNodeClass(context, 'bg-color', BootstrapClasses.background, 'p')
        },
        'display': function (context) {
            return toggleNodeClass(context, 'display', BootstrapClasses.display, 'div')
        },
        'position': function (context) {
            return toggleNodeClass(context, 'position', BootstrapClasses.position, 'div')
        },
        'border': function (context) {
            return toggleNodeClass(context, 'border', BootstrapClasses.border, 'p')
        },
        'sizing': function (context) {
            return toggleNodeClass(context, 'sizing', BootstrapClasses.sizing, 'p')
        },
        'spacing': function (context) {
            return toggleNodeClass(context, 'spacing', BootstrapClasses.spacing, 'p')
        },
        'tooltip': function (context) {
            var self = this;
            // ui has renders to build ui elements.
            //  - you can create a button with `ui.button`
            var ui = $.summernote.ui;
            // Here we create a button


            context.memo('button.tooltip', function () {
                var button = ui.button({
                    contents: '<span data-toggle="modal" data-target="#tooltipInsertModal">Tooltip</span>',
                    // tooltip for button
                    tooltip: 'Tooltip',
                    click: function (e) {
                        var nodeType = 0;
                        var baseNode = false;
                        var existingContent = '';
                        if (window.getSelection) {
                            var sel = window.getSelection();
                            baseNode = window.getSelection().baseNode;
                            if (sel.rangeCount) {
                                var range = sel.getRangeAt(0).cloneRange();
                                // var range = document.createRange();
                                // range.selectNode(r.startContainer)

                                existingContent = range.toString();
                                nodeType = sel.focusNode.nodeType = 3;
                            }
                        }

                        $('#tooltipModalSaveBtn').on('click', function (e) {
                            e.preventDefault();
                            var title = $('#tooltipTitle').val();
                            var placement = $('#tooltipPlacement').val();
                            $('#tooltipTitle').val('');
                            var span = document.createElement('span');
                            span.setAttribute('data-toggle', 'tooltip');
                            span.setAttribute('data-placement', placement);
                            span.setAttribute('title', title);

                            if (nodeType == 3) {
                                //here nodeType 3 is equal to text node
                                try {
                                    if (existingContent.length > 0) {
                                        range.surroundContents(span);
                                        sel.removeAllRanges();
                                        sel.addRange(range);
                                    }
                                } catch (e) {
                                    console.log(e);
                                }

                            } else {
                                if (baseNode) {
                                    var basePar = baseNode.parentNode;
                                    basePar.setAttribute('data-toggle', 'tooltip');
                                    basePar.setAttribute('data-placement', placement);
                                    basePar.setAttribute('title', title);
                                }
                            }
                        });
                    }
                });
                return button.render();
            })


        },

        'popover': function (context) {
            var self = this;
            // ui has renders to build ui elements.
            //  - you can create a button with `ui.button`
            var ui = $.summernote.ui;
            // Here we create a button


            context.memo('button.popover', function () {
                var button = ui.button({
                    contents: '<span data-toggle="modal" data-target="#popoverInsertModal">Popover</span>',
                    // tooltip for button
                    tooltip: 'Popover',
                    click: function (e) {
                        var nodeType = 0;
                        var baseNode = false;
                        var existingContent = '';
                        if (window.getSelection) {
                            var sel = window.getSelection();
                            baseNode = window.getSelection().baseNode;
                            if (sel.rangeCount) {
                                //  var r = sel.getRangeAt(0).cloneRange();
                                var range = sel.getRangeAt(0).cloneRange();
                                // var range = document.createRange();
                                //   range.selectNode(r.startContainer);

                                existingContent = range.toString();
                                nodeType = sel.focusNode.nodeType = 3;
                            }
                        }

                        $('#popoverModalSaveBtn').on('click', function (e) {
                            e.preventDefault();
                            var title = $('#popoverTitle').val();
                            var placement = $('#popoverPlacement').val();
                            var content = $('#popoverContent').val();
                            // make input  blank
                            $('#popoverTitle').val('');
                            $('#popoverContent').val('');

                            //here nodeType 3 is equal to text node
                            var span = document.createElement('span');
                            span.setAttribute('data-toggle', 'popover');
                            span.setAttribute('data-placement', placement);
                            span.setAttribute('title', title);
                            span.setAttribute('data-content', content);

                            if (nodeType == 3) {
                                if (existingContent.length > 0) {
                                    try {
                                        range.surroundContents(span);
                                        sel.removeAllRanges();
                                        sel.addRange(range);
                                    } catch (e) {
                                        console.log(e);
                                    }

                                }
                            } else {
                                if (baseNode) {
                                    var basePar = baseNode.parentNode;
                                    basePar.setAttribute('data-toggle', 'popover');
                                    basePar.setAttribute('data-placement', placement);
                                    basePar.setAttribute('title', title);
                                    basePar.setAttribute('data-content', content);
                                }
                            }
                        });
                    }
                });
                return button.render();
            })


        }
    })
    ;
}))
;
