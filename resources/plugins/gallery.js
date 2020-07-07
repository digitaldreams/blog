var ImageGalleryList = [];
var ajaxImageSource = [];

function renderImages(list) {
    var keys = Object.keys(list);
    var html = '';
    for (i = 0; i < keys.length; i++) {
        var img = list[i];
        if (img.thumbnails.length < 1) {
            continue;
        }
        var figureHtml = '<label for="imageGalleryCheckbox_' + i + '">' +
            '<picture class="figure cursor-pointer">\n';

        for (var th = 0; th < img.thumbnails.length; th++) {
            figureHtml += '<source srcset="' + img.thumbnails[th] + '">'
        }

        figureHtml += '<img src="' + img.thumbnails[0] + '" width="120px" alt="' + img.caption + '" title="' + img.caption + '">';
        figureHtml += '</picture>\n';
        figureHtml += '<input type="checkbox" name="insertGalleryImages" class="insertGalleryImagesCheckbox" id="imageGalleryCheckbox_' + i + '" value="' + i + '" >' +
            '</label>';
        html += figureHtml
    }
    return html;
}

function generatePictureTag(image) {
    var html = '<picture class="figure">\n';
    for (var ins = 0; ins < image.thumbnails.length; ins++) {
        html += '<source srcset="' + image.thumbnails[ins] + '" media="(min-width::576px)">';
    }
    if (image.urls.length > 1) {
        html += '<source srcset="' + image.urls[1] + '">'
    }
    if (image.urls.length > 0) {
        html += '<img src="' + image.urls[0] + '" class="figure-img img-fluid rounded" alt="' + image.caption + '" title="' + image.caption + '"/>\n';
    }
    html += '</picture>\n';
    return html
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
        'gallery': function (context) {
            var self = this;
            if (typeof context.options.gallery === 'undefined') {
                context.options.gallery = {};
            }
            if (typeof context.options.gallery.list === 'undefined') {
                context.options.gallery.list = ImageGalleryList;
            }

            // ui has renders to build ui elements.
            //  - you can create a button with `ui.button`
            var ui = $.summernote.ui;
            // Here we create a button


            context.memo('button.gallery', function () {
                var button = ui.button({
                    contents: '<span data-toggle="modal" data-target="#summernoteImageInsertModal">Gallery</span>',
                    // tooltip for button
                    tooltip: 'Gallery',
                    callback: function () {
                        if (typeof context.options.gallery.url === 'undefined') {
                            $("#imagePreviewContainer").html(renderImages(context.options.gallery.list));
                        } else {
                            $.get(context.options.gallery.url, function (data) {
                                $("#imagePreviewContainer").html(renderImages(data.data));
                                ajaxImageSource = data.data;
                            });
                        }
                        $(document).on('click', "#insertImageSaveBtn", function () {
                            var img = [];

                            $('input[name=insertGalleryImages]:checked').each(function () {
                                img.push(this.value);
                            });
                            if (img.length == 1) {
                                var image = ajaxImageSource[img];
                                pasteHtmlAtCaret(generatePictureTag(image));
                            } else if (img.length > 1) {
                                var images = img;
                                var randNumber = Math.floor(Math.random() * 20);
                                var indicators = '<ol class="carousel-indicators">\n';
                                var inner = '<div class="carousel-inner">\n';

                                for (var i = 0; i < images.length; i++) {
                                    var image = ajaxImageSource[images[i]];
                                    var active = i == 0 ? 'active' : '';
                                    indicators += ' <li data-target="#carouselExampleIndicators_' + randNumber + '" data-slide-to="' + i + '" class="' + active + '"></li>\n';
                                    inner += '     <div class="carousel-item ' + active + '">\n';
                                    inner += generatePictureTag(image);
                                    if (image.caption.length > 0) {
                                        inner += '<div class="carousel-caption d-none d-md-block">\n' +
                                            '                <h5>' + image.caption + '</h5>\n' +
                                            '            </div>\n';
                                    }
                                    inner += '</div>\n'

                                }
                                indicators += '</ol>\n';
                                inner += '</div>\n';

                                var control = ' <a class="carousel-control-prev" href="#carouselExampleIndicators_' + randNumber + '" role="button" data-slide="prev">\n' +
                                    '        <span class="carousel-control-prev-icon" aria-hidden="true"></span>\n' +
                                    '        <span class="sr-only">Previous</span>\n' +
                                    '    </a>\n' +
                                    '    <a class="carousel-control-next" href="#carouselExampleIndicators_' + randNumber + '" role="button" data-slide="next">\n' +
                                    '        <span class="carousel-control-next-icon" aria-hidden="true"></span>\n' +
                                    '        <span class="sr-only">Next</span>\n' +
                                    '    </a>\n';
                                var slider = '<div id="carouselExampleIndicators_' + randNumber + '" class="carousel slide" data-ride="carousel">\n';
                                slider += indicators;
                                slider += inner;
                                slider += control;

                                slider += '</div>';
                                pasteHtmlAtCaret(slider);
                            }
                            var imgchk = document.getElementsByClassName('insertGalleryImagesCheckbox');
                            for (var c = 0; c < imgchk.length; c++) {
                                imgchk[c].checked = false;
                            }
                        });

                    }
                });
                return button.render();
            })


        },
    });
}));
