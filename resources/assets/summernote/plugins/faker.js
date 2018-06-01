var FakerFields = [
    [
        'lorem.sentence', 'lorem.sentences', 'lorem.paragraph', 'lorem.paragraphs', 'lorem.text', 'lorem.lines', 'lorem.slug', 'lorem.word', 'lorem.words'
    ],
    [
        'name.firstName', 'name.lastName', 'name.findName', 'name.jobTitle', 'name.title', 'name.jobDescriptor',
        'name.jobArea', 'name.jobType'
    ],
    [
        'internet.email', 'internet.password', 'internet.userName', 'internet.url', 'internet.domainName', 'internet.ip', 'internet.userAgent', 'internet.color'
    ],
    [
        'phone.phoneNumber', 'address.streetAddress', 'address.streetName', 'address.city', 'address.secondaryAddress',
        'address.state', 'address.zipCode', 'address.county', 'address.countryCode',
    ],
    [
        'date.future', 'date.past', 'date.between', 'date.recent', 'date.soon', 'date.month', 'date.weekday'
    ],
    [
        'company.companyName', 'commerce.productName', 'commerce.price', 'commerce.department', 'commerce.color'
    ],
];

function fakerData(key) {
    return faker.fake("{{" + key + "}}");
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
        'fakerPlugin': function (context) {
            var self = this;
            if (typeof context.options.fakerPlugin === 'undefined') {
                context.options.fakerPlugin = {};
            }
            if (typeof context.options.fakerPlugin.fields === 'undefined') {
                context.options.fakerPlugin.fields = FakerFields;
            }
            // ui has renders to build ui elements.
            //  - you can create a button with `ui.button`
            var ui = $.summernote.ui;


            context.memo('button.fakerPlugin', function () {
                return ui.buttonGroup([
                    ui.button({
                        className: 'dropdown-toggle',
                        contents: 'Faker',
                        tooltip: 'Generate fake data',
                        data: {
                            toggle: 'dropdown'
                        }
                    }),
                    ui.dropdown({
                        className: 'dropdown-style',
                        items: context.options.fakerPlugin.fields,
                        template: function (item) {
                            var html = '';
                            if (Array.isArray(item)) {
                                for (var c = 0; c < item.length; c++) {
                                    var names = item[c].split(".");
                                    if (names.length > 1) {
                                        var name = names[names.length - 1];
                                    } else {
                                        var name = item[c]
                                    }
                                    var formattedName = name.replace(/([A-Z])/g, ' $1')
                                        .replace(/^./, function (str) {
                                            return str.toUpperCase();
                                        })
                                    html += '<span class="badge badge-secondary"' + 'data-key="' + item[c] + '"' + ' data-toggle="tooltip" title="' + formattedName + '">' + formattedName + '</span>&nbsp;&nbsp;';
                                }
                            } else {
                                html = '<span>' + item + '</span>';
                            }
                            return html + '<hr/>';
                        },
                        click: function (event, namespace, value) {

                            event.preventDefault();
                            var text = fakerData($(event.target).data('key'));

                            context.invoke('editor.restoreRange');
                            context.invoke('editor.focus');
                            context.invoke("editor.insertText", text);

                        }
                    })
                ]).render();
                return $optionList;
            });

        }
    });
}));
