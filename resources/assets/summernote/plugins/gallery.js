var ImageGalleryList = [
    {
        src: 'https://scontent-sit4-1.xx.fbcdn.net/v/t31.0-8/18766655_1310770569042585_4897233834252565325_o.jpg?oh=5d67b76bcaa228bc8258348111124bd9&oe=5B3A65B2',
        caption: 'Brindhabon'
    },
    {
        src: 'https://scontent-sit4-1.xx.fbcdn.net/v/t1.0-9/18118782_1477908295605867_7125244197043312913_n.jpg?oh=00fa12487f35d6fe7967fdeba7c87a3e&oe=5B37AE05',
        caption: 'Brindhabon'
    },
    {
        src: 'https://scontent-sit4-1.xx.fbcdn.net/v/t1.0-9/18118782_1477908295605867_7125244197043312913_n.jpg?oh=00fa12487f35d6fe7967fdeba7c87a3e&oe=5B37AE05',
        caption: 'Brindhabon'
    },
    {
        src: 'https://scontent-sit4-1.xx.fbcdn.net/v/t31.0-8/17973562_2118342881725306_3660611057400938150_o.jpg?oh=147cee45af62a39dec2c426894cac1f7&oe=5B4446ED',
        caption: 'Brindhabon'
    },
    'https://scontent-sit4-1.xx.fbcdn.net/v/t31.0-8/17973562_2118342881725306_3660611057400938150_o.jpg?oh=147cee45af62a39dec2c426894cac1f7&oe=5B4446ED'

];

function renderImages(list) {
    var keys = Object.keys(list);
    var html = '';
    for (i = 0; i < keys.length; i++) {
        var figureHtml = '';
        figureHtml = '<figure class="figure cursor-pointer">\n';
        var img = list[i];
        if (typeof img === 'string') {
            img = {url: img}
        }
        if (typeof img.thumbnail === 'undefined') {
            img.thumbnail = img.url;
        }
        if (typeof img.url === 'undefined') {
            img.url = img.thumbnail;
        }
        if (typeof img.caption === 'undefined') {
            img.caption = '';
        }
        figureHtml += '<label for="imageGalleryCheckbox_' + i + '">' +
            '<input type="checkbox" name="insertGalleryImages" class="insertGalleryImagesCheckbox" id="imageGalleryCheckbox_' + i + '" value="' + img.url + '" >' +
            '<img src="' + img.thumbnail + '" class="figure-img img-fluid rounded" width="100px" title="' + img.caption + '"/></label>' +

            '\n';
        if (img.caption.length > 0) {
            var caption = img.caption;
            if (caption.length > 15) {
                caption = img.caption.substr(0, 15) + '...';
            }
            figureHtml += '<figcaption class="figure-caption text-center">' +caption +'</figcaption>\n';
        }

        figureHtml += '</figure>\n';
        html += figureHtml
    }
    return html;
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
                                $("#imagePreviewContainer").html(renderImages(data));
                            });
                        }
                        $(document).on('click', "#insertImageSaveBtn", function () {
                            var img = [];

                            $('input[name=insertGalleryImages]:checked').each(function () {
                                img.push(this.value);
                            });
                            if (img.length == 1) {
                                var src = img;
                                var fig = '<figure class="figure">\n';
                                fig += '<img src="' + src + '" class="figure-img img-fluid rounded" alt="" title=""/>\n';

                                fig += '</figure>\n';
                                pasteHtmlAtCaret(fig);
                            } else if (img.length > 1) {
                                var images = img;
                                var randNumber = Math.floor(Math.random() * 20);
                                var indicators = '<ol class="carousel-indicators">\n';
                                var inner = '<div class="carousel-inner">\n';

                                for (var i = 0; i < images.length; i++) {
                                    var active = i == 0 ? 'active' : '';
                                    indicators += ' <li data-target="#carouselExampleIndicators_' + randNumber + '" data-slide-to="' + i + '" class="' + active + '"></li>\n';
                                    inner += '     <div class="carousel-item ' + active + '">\n' +
                                        '            <img class="d-block w-100" src="' + images[i] + '" alt="{{lorem.sentence}}">\n';
                                    if (false) {
                                        inner += '<div class="carousel-caption d-none d-md-block">\n' +
                                            '                <h5>{{lorem.words}}</h5>\n' +
                                            '                <p>{{lorem.sentence}}</p>\n' +
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
