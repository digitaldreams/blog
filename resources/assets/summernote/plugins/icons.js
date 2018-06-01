var IconList = {
    'mostUsed': ['fa fa-home', 'fa fa-plus', 'fa fa-pencil', 'fa fa-trash', 'fa fa-user', 'fa fa-envelope', 'fa fa-image',
        'fa fa-file', 'fa fa-folder', 'fa fa-lock', 'fa fa-list', 'fa fa-link', 'fa fa-times', 'fa fa-search',
    ],
    'a': ['fa fa-info-circle', 'fa fa-warning', 'fa fa-ban', 'fa fa-upload', 'fa fa-download', 'fa fa-sign-in', 'fa fa-sign-out',
        'fa fa-calendar', 'fa fa-eye', 'fa fa-ellipsis-h', 'fa fa-ellipsis-v', 'fa fa-check', 'fa fa-check-square-o',
        'fa fa-arrow-right', 'fa fa-arrow-left', 'fa fa-arrow-up', 'fa fa-arrow-down',
    ],

    'web': ['fa fa-calculator', 'fa fa-calendar', 'fa fa-calendear-check-o', 'fa fa-calendar-minus-o', 'fa fa-calendar-o',
        'fa fa-calender-plus-o', 'fa fa-calendar-times-o', 'fa fa-book', 'fa fa-bar', 'fa fa-arrows', 'fa fa-arrows-h',
        'fa fa-arrows-v', 'fa fa-asterisk', 'fa fa-balance-scale', 'fa fa-camera', 'fa fa-camera-retro', 'fa fa-binoculars',
    ],
    'c': ['fa fa-bullhorn', 'fa fa-bell', 'fa fa-bell-o', 'fa fa-bell-slash', 'fa fa-bookmark', 'fa fa-bookmark-o',
        'fa fa-clock', 'fa fa-cloud', 'fa fa-tags', 'fa fa-comment', 'fa fa-comments','fa fa-send','fa fa-share', 'fa fa-mail-forward','fa fa-thumbs-up', 'fa fa-thumbs-down',
        'fa fa-mobile','fa fa-phone',
    ],
    'e': ['fa fa-navicon','fa fa-map','fa fa-map-marker','fa fa-map-pin','fa fa-file-zip-o', 'fa fa-file-text-o', 'fa fa-file-pdf-o', 'fa fa-file-excel-o', 'fa fa-file-word-o',
        'fa fa-file-video-o', 'fa fa-audio-o', 'fa fa-file-code-o','fa fa-car', 'fa fa-bus', 'fa fa-cab', 'fa fa-bicycle'],
    'direction': ['fa fa-arrows', 'fa fa-arrows-h', 'fa fa-arrows-v', 'fa fa-hand-o-right', 'fa fa-hand-o-left', 'fa fa-hand-o-up',
        'fa fa-hand-o-down', 'fa fa-chevron-right', 'fa fa-chevron-left', 'fa fa-chevron-up', 'fa fa-chevron-down', 'fa fa-chevron-circle-right',
        'fa fa-chevron-circle-left', 'fa fa-chevron-circle-up', 'fa fa-chevron-circle-down', 'fa fa-',],
    'brands': ['fa fa-facebook-official', 'fa fa-linkedin', 'fa fa-instagram', 'fa fa-twitter', 'fa fa-google-plus', 'fa fa-youtube', 'fa fa-wordpress', 'fa fa-windows', 'fa fa-linux', 'fa fa-amazon', 'fa fa-android', 'fa fa-apple', 'fa fa-bitbucket'],
    'payment': ['fa fa-credit-card', 'fa fa-cc-visa', 'fa fa-cc-mastercard', 'fa fa-paypal', 'fa fa-cc-amex', 'fa fa-cc-dinars-club', 'fa fa-cc-discover', 'fa fa-cc-jcb',
        'fa fa-cc-paypal', 'fa fa-cc-stripe', 'fa fa-bitcoin', 'fa fa-google-wallet'],
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
        'icons': function (context) {
            var self = this;
            if (typeof context.options.icons === 'undefined') {
                context.options.icons = {};
            }
            if (typeof context.options.icons.classTags === 'undefined') {
                context.options.icons.classTags = IconList;
            }
            // ui has renders to build ui elements.
            //  - you can create a button with `ui.button`
            var ui = $.summernote.ui;

            context.memo('button.icons', function () {
                return ui.buttonGroup([
                    ui.button({
                        className: 'dropdown-toggle',
                        contents: 'icons',
                        tooltip: 'Icons',
                        data: {
                            toggle: 'dropdown'
                        }
                    }),
                    ui.dropdown({
                        className: 'dropdown-style',
                        items: Object.keys(context.options.icons.classTags),
                        template: function (item) {
                            var key = item;
                            var itemList = context.options.icons.classTags[item];
                            if (item.length >= 2) {
                                var html = '<p class="bg-light m-0"><u>' + item + '</u>  &nbsp;';

                            } else {
                                var html = '<p class="bg-light m-0 p-0">  &nbsp;';
                            }

                            if (Array.isArray(itemList)) {
                                for (var c = 0; c < itemList.length; c++) {
                                    var className = ' class="' + itemList[c] + '"';
                                    html += '<i' + className + ' data-toggle="tooltip" title="' + itemList[c] + '"></i>&nbsp;&nbsp;';
                                }
                            } else {
                                var className = ' class="' + itemList + '"';
                                html = '<i' + className + '>' + itemList + '</i>';
                            }
                            html += '</p>'
                            return html;
                        },
                        click: function (event, namespace, value) {

                            event.preventDefault();
                            //  value = value || $(event.target).closest('[data-value]').data('value');
                            context.invoke('editor.restoreRange');
                            context.invoke('editor.focus');
                            pasteHtmlAtCaret('<i' + ' class="' + $(event.target).attr('class') + '"' + '></i>\n');

                        }
                    })
                ]).render();
                return $optionList;
            });
        }
    });
}));
