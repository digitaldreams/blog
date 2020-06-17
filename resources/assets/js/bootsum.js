function pasteHtmlAtCaret(html, selection) {
    var sel, range;
    if (typeof selection == 'undefined') {
        selection = window.getSelection;
    }
    if (selection) {
        // IE9 and non-IE

        sel = selection();
        if (sel.getRangeAt && sel.rangeCount) {
            range = sel.getRangeAt(0);
            range.deleteContents();

            // Range.createContextualFragment() would be useful here but is
            // only relatively recently standardized and is not supported in
            // some browsers (IE9, for one)
            var el = document.createElement("div");
            el.innerHTML = html;
            var frag = document.createDocumentFragment(), node, lastNode;
            while ((node = el.firstChild)) {
                lastNode = frag.appendChild(node);
            }
            range.insertNode(frag);

            // Preserve the selection
            if (lastNode) {
                range = range.cloneRange();
                range.setStartAfter(lastNode);
                range.collapse(true);
                sel.removeAllRanges();
                sel.addRange(range);
            }
        }
    } else if (document.selection && document.selection.type != "Control") {
        // IE < 9
        document.selection.createRange().pasteHTML(html);
    }
}

function uploadImages(form) {
    var form = $(form);

    data = new FormData($(form)[0]);
    data.append("action", 'upload');
    $.ajax({
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        url: form.attr('action'),
        type: form.attr('method'),
        success: function (url) {
            window.location.reload();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + " " + errorThrown);
        }
    });
}

function readURLs(input, box) {
    if (input.files && input.files[0]) {
        var i;
        for (i = 0; i < input.files.length; ++i) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#' + box).append('<div class="col-sm-2"><img class="img-thumbnail" src="' + e.target.result + '"></div>');
            }
            reader.readAsDataURL(input.files[i]);
        }
    }
}

function triggerSummernoteChange(element) {

    $(element).summernote('triggerEvent', 'change');
    // form.submit();
    return true;
}

function sendFile(file, callback, welEditable) {
    data = new FormData();
    data.append("file", file);
    var token = document.head.querySelector('meta[name="csrf-token"]');
    if (token) {
        data.append("_token", token.content);
    } else {
        console.error('CSRF token not found');
    }
    $.ajax({
        url: '/api/photo/photos',
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST',
        success: function (url) {
            try {
                var image = $('<img>').attr('src', url[0].url).attr('alt', url[0].caption).attr('title', url[0].title);
                $('#summernote').summernote('insertNode', image[0]);
            } catch (e) {
                console.log(e);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + " " + errorThrown);
        }
    });
}

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



var BootstrapClasses = {

    background: [
        'bg-primary', 'bg-secondary', 'bg-success', 'bg-danger', 'bg-warning', 'bg-info', 'bg-light', 'bg-dark'
    ],
    text: [
        'text-primary', 'text-secondary', 'text-success', 'text-danger', 'text-warning', 'text-info', 'text-light', 'text-dark',
        'lead', 'text-justify', 'text-left', 'text-center', 'text-right', 'text-lowercase', 'text-uppercase', 'font-italic',
        'text-capitalize', 'font-weight-bold', 'font-weight-normal', 'font-weight-light', 'text-nowrap', 'text-truncate',
        //'align-baseline', 'align-top', 'align-middle', 'align-bottom', 'align-text-top', 'align-text-bottom'
    ],
    form: [
        'form-group', 'form-control', 'form-control-sm', 'form-control-lg', 'input-group', 'input-group-prepend',
        'input-group-append', 'input-group-text', 'form-check', 'form-check-input', 'form-control-file', 'form-control-plaintext',
        'form-check-label', 'form-row', 'col', 'col-form-label', 'col-form-label-sm', ' col-form-label-lg', 'col-auto',
        'custom-select', 'custom-control', 'form-inline', 'form-text',
    ],
    row: [
        'row', 'col',
        'col-1', 'col-3', 'col-2', 'col-4', 'col-5', 'col-6', 'col-7', 'col-8', 'col-9', 'col-10', 'col-11', 'col-12',
        'col-sm-1', 'col-sm-3', 'col-sm-2', 'col-sm-4', 'col-sm-5', 'col-sm-6', 'col-sm-7', 'col-sm-8', 'col-sm-9', 'col-sm-10', 'col-sm-11', 'col-sm-12',
        'col-md-1', 'col-md-3', 'col-md-2', 'col-md-4', 'col-md-5', 'col-md-6', 'col-md-7', 'col-md-8', 'col-md-9', 'col-md-10', 'col-md-11', 'col-md-12',
        'col-lg-1', 'col-lg-3', 'col-mlglg-2', 'col-lg-4', 'col-lg-5', 'col-lg-6', 'col-lg-7', 'col-lg-8', 'col-lg-9', 'col-lg-10', 'col-lg-11', 'col-lg-12',
    ],
    sizing: [
        'w-25', 'w-50', 'w-75', 'w-100', 'h-25', 'h-50', 'h-75', 'h-100', 'mw-100', 'mh-100'
    ],
    spacing: [
        'm-0', 'm-1', 'm-2', 'm-3', 'm-4', 'm-5', 'm-auto', 'mx-0', 'mx-1', 'mx-2', 'mx-3', 'mx-4', 'mx-5', 'my-0',
        'my-1', 'my-2', 'my-3', 'my-4', 'my-5', 'my-auto',
        'p-0', 'p-1', 'p-2', 'p-3', 'p-4', 'p-5', 'p-auto', 'px-0', 'px-1', 'px-2', 'px-3', 'px-4', 'px-5', 'py-0',
        'py-1', 'py-2', 'py-3', 'py-4', 'py-5', 'py-auto'
    ],

    table: [
        'table', 'table-dark', 'thead-light', 'thead-dark', 'table-striped', 'table-dark', 'table-bordered', 'table-hover',
        'table-sm', 'table-active', 'table-responsive'
    ],
    ul: [
        'list-group', 'list-group-flush', 'list-group-item', 'list-group-item-action', 'list-group-item-primary',
        'list-group-item-secondary', 'list-group-item-success', 'list-group-item-danger', 'list-group-item-warning',
        'list-group-item-info', 'list-group-item-light', 'list-group-item-dark',
    ],
    card: [
        'card', 'card-img-top', 'card-body', 'card-header', 'card-footer', 'card-title', 'card-text', 'card-link', 'card-header-tabs',
        'card-img-bottom', 'card-img-overlay', 'card-img', 'card-group', 'card-deck', 'card-columns',
    ],

    alert: [
        'alert', 'alert-primary', 'alert-secondary', 'alert-success', 'alert-danger', 'alert-warning',
        'alert-info', 'alert-light', 'alert-dark'
    ],
    heading: [
        'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'display-1', 'display-2', 'display-3', 'display-4', 'display-5', 'display-6'
    ],
    button: [
        'btn', 'btn-primary', 'btn-secondary', 'btn-success', 'btn-danger', 'btn-warning', 'btn-info',
        'btn-light', 'btn-dark', 'btn-group', 'btn-group-toggle', 'btn-toolbar'
    ],
    display: [
        'd-none', 'd-inline', 'd-inline-block', 'd-block', 'd-inline', 'd-table-cell', 'd-able-row', 'd-flex', 'd-inline-flex',
        'flex-row', 'flex-row-reverse', 'flex-column', 'flex-column-reverse', 'float-left', 'float-right', 'float-none'
    ],
    position: [
        'position-static', 'position-relative', 'position-absolute', 'position-fixed', 'position-sticky',
        'fixed-top', 'fixed-bottom', 'sticky-top'
    ],

    badge: [
        'badge', 'badge-primary', 'badge-secondary', 'badge-success', 'badge-danger', 'badge-warning',
        'badge-info', 'badge-light', 'badge-dark'
    ],
    nav: [
        'nav', 'nav-item', 'nav-link', 'nav-pills', 'nav-tabs', 'nav-fill', 'nav-justified', 'justify-content-center', 'justify-content-end'
    ],
    navbar: [
        'navbar', 'navbar-light', 'navbar-brand', 'navbar-toggler', 'navbar-toggler-icon', 'navbar-collapse', 'navbar-nav',
        'navbar-text', 'navbar-expand-lg', 'navbar-dark', 'fixed-top', 'fixed-bottom', 'sticky-top', '',
    ],
    border: [
        'border', 'border-top', 'border-right', 'border-bottom', 'border-left',
        'border-0', 'border-top-0', 'border-right-0', 'border-bottom-0', 'border-left-0',
        'border-primary', 'border-secondary', 'border-success', 'border-danger', 'border-warning', 'border-info',
        'border-light', 'border-dark', 'border-white',
        'rounded', 'rounded-top', 'rounded-right', 'rounded-bottom', 'rounded-left', 'rounded-circle', 'rounded-0'
    ],
    others: [
        'active', 'disabled', 'blockquote', 'blockquote-footer', 'breadcrumb', 'breadcrumb-item',
    ],
    dropdown: [
        'dropdown', 'dropdown-menu', 'dropdown-toggle', 'dropdown-item', 'dropdown-divider'
    ],
    icons: [
        'fa-2x', 'fa-3x', 'fa-4x', 'fa-5x'
    ]

}
var fontAwesomeIcons = [
    ["fa fa-address-book", "fa fa-address-book-o", "fa fa-address-card", "fa fa-address-card-o", "fa fa-bandcamp", "fa fa-bath", "fa fa-bath", "fa fa-id-card", "fa fa-id-card-o", "fa fa-eercast", "fa fa-envelope-open", "fa fa-envelope-open-o", "fa fa-etsy", "fa fa-free-code-camp", "fa fa-grav", "fa fa-handshake-o", "fa fa-id-badge", "fa fa-id-card", "fa fa-id-card-o", "fa fa-imdb"], ["fa fa-linode", "fa fa-meetup", "fa fa-microchip", "fa fa-podcast", "fa fa-quora", "fa fa-ravelry", "fa fa-bath", "fa fa-shower", "fa fa-snowflake-o", "fa fa-superpowers", "fa fa-telegram", "fa fa-thermometer-full", "fa fa-thermometer-empty", "fa fa-thermometer-quarter", "fa fa-thermometer-half", "fa fa-thermometer-three-quarters", "fa fa-thermometer-full", "fa fa-thermometer-empty", "fa fa-thermometer-full", "fa fa-thermometer-half"], ["fa fa-thermometer-quarter", "fa fa-thermometer-three-quarters", "fa fa-window-close", "fa fa-window-close-o", "fa fa-user-circle", "fa fa-user-circle-o", "fa fa-user-o", "fa fa-address-card", "fa fa-address-card-o", "fa fa-window-close", "fa fa-window-close-o", "fa fa-window-maximize", "fa fa-window-minimize", "fa fa-window-restore", "fa fa-wpexplorer", "fa fa-address-book", "fa fa-address-book-o", "fa fa-address-card", "fa fa-address-card-o", "fa fa-adjust"], ["fa fa-american-sign-language-interpreting", "fa fa-anchor", "fa fa-archive", "fa fa-area-chart", "fa fa-arrows", "fa fa-arrows-h", "fa fa-arrows-v", "fa fa-american-sign-language-interpreting", "fa fa-assistive-listening-systems", "fa fa-asterisk", "fa fa-at", "fa fa-audio-description", "fa fa-car", "fa fa-balance-scale", "fa fa-ban", "fa fa-university", "fa fa-bar-chart", "fa fa-bar-chart", "fa fa-barcode", "fa fa-bars"], ["fa fa-bath", "fa fa-bath", "fa fa-battery-full", "fa fa-battery-empty", "fa fa-battery-quarter", "fa fa-battery-half", "fa fa-battery-three-quarters", "fa fa-battery-full", "fa fa-battery-empty", "fa fa-battery-full", "fa fa-battery-half", "fa fa-battery-quarter", "fa fa-battery-three-quarters", "fa fa-bed", "fa fa-beer", "fa fa-bell", "fa fa-bell-o", "fa fa-bell-slash", "fa fa-bell-slash-o", "fa fa-bicycle"], ["fa fa-binoculars", "fa fa-birthday-cake", "fa fa-blind", "fa fa-bluetooth", "fa fa-bluetooth-b", "fa fa-bolt", "fa fa-bomb", "fa fa-book", "fa fa-bookmark", "fa fa-bookmark-o", "fa fa-braille", "fa fa-briefcase", "fa fa-bug", "fa fa-building", "fa fa-building-o", "fa fa-bullhorn", "fa fa-bullseye", "fa fa-bus", "fa fa-taxi", "fa fa-calculator"], ["fa fa-calendar", "fa fa-calendar-check-o", "fa fa-calendar-minus-o", "fa fa-calendar-o", "fa fa-calendar-plus-o", "fa fa-calendar-times-o", "fa fa-camera", "fa fa-camera-retro", "fa fa-car", "fa fa-caret-square-o-down", "fa fa-caret-square-o-left", "fa fa-caret-square-o-right", "fa fa-caret-square-o-up", "fa fa-cart-arrow-down", "fa fa-cart-plus", "fa fa-cc", "fa fa-certificate", "fa fa-check", "fa fa-check-circle", "fa fa-check-circle-o"], ["fa fa-check-square", "fa fa-check-square-o", "fa fa-child", "fa fa-circle", "fa fa-circle-o", "fa fa-circle-o-notch", "fa fa-circle-thin", "fa fa-clock-o", "fa fa-clone", "fa fa-times", "fa fa-cloud", "fa fa-cloud-download", "fa fa-cloud-upload", "fa fa-code", "fa fa-code-fork", "fa fa-coffee", "fa fa-cog", "fa fa-cogs", "fa fa-comment", "fa fa-comment-o"], ["fa fa-commenting", "fa fa-commenting-o", "fa fa-comments", "fa fa-comments-o", "fa fa-compass", "fa fa-copyright", "fa fa-creative-commons", "fa fa-credit-card", "fa fa-credit-card-alt", "fa fa-crop", "fa fa-crosshairs", "fa fa-cube", "fa fa-cubes", "fa fa-cutlery", "fa fa-tachometer", "fa fa-database", "fa fa-deaf", "fa fa-deaf", "fa fa-desktop", "fa fa-diamond"], ["fa fa-dot-circle-o", "fa fa-download", "fa fa-id-card", "fa fa-id-card-o", "fa fa-pencil-square-o", "fa fa-ellipsis-h", "fa fa-ellipsis-v", "fa fa-envelope", "fa fa-envelope-o", "fa fa-envelope-open", "fa fa-envelope-open-o", "fa fa-envelope-square", "fa fa-eraser", "fa fa-exchange", "fa fa-exclamation", "fa fa-exclamation-circle", "fa fa-exclamation-triangle", "fa fa-external-link", "fa fa-external-link-square", "fa fa-eye"], ["fa fa-eye-slash", "fa fa-eyedropper", "fa fa-fax", "fa fa-rss", "fa fa-female", "fa fa-fighter-jet", "fa fa-file-archive-o", "fa fa-file-audio-o", "fa fa-file-code-o", "fa fa-file-excel-o", "fa fa-file-image-o", "fa fa-file-video-o", "fa fa-file-pdf-o", "fa fa-file-image-o", "fa fa-file-image-o", "fa fa-file-powerpoint-o", "fa fa-file-audio-o", "fa fa-file-video-o", "fa fa-file-word-o", "fa fa-file-archive-o"], ["fa fa-film", "fa fa-filter", "fa fa-fire", "fa fa-fire-extinguisher", "fa fa-flag", "fa fa-flag-checkered", "fa fa-flag-o", "fa fa-bolt", "fa fa-flask", "fa fa-folder", "fa fa-folder-o", "fa fa-folder-open", "fa fa-folder-open-o", "fa fa-frown-o", "fa fa-futbol-o", "fa fa-gamepad", "fa fa-gavel", "fa fa-cog", "fa fa-cogs", "fa fa-gift"], ["fa fa-glass", "fa fa-globe", "fa fa-graduation-cap", "fa fa-users", "fa fa-hand-rock-o", "fa fa-hand-lizard-o", "fa fa-hand-paper-o", "fa fa-hand-peace-o", "fa fa-hand-pointer-o", "fa fa-hand-rock-o", "fa fa-hand-scissors-o", "fa fa-hand-spock-o", "fa fa-hand-paper-o", "fa fa-handshake-o", "fa fa-deaf", "fa fa-hashtag", "fa fa-hdd-o", "fa fa-headphones", "fa fa-heart", "fa fa-heart-o"], ["fa fa-heartbeat", "fa fa-history", "fa fa-home", "fa fa-bed", "fa fa-hourglass", "fa fa-hourglass-start", "fa fa-hourglass-half", "fa fa-hourglass-end", "fa fa-hourglass-end", "fa fa-hourglass-half", "fa fa-hourglass-o", "fa fa-hourglass-start", "fa fa-i-cursor", "fa fa-id-badge", "fa fa-id-card", "fa fa-id-card-o", "fa fa-picture-o", "fa fa-inbox", "fa fa-industry", "fa fa-info"], ["fa fa-info-circle", "fa fa-university", "fa fa-key", "fa fa-keyboard-o", "fa fa-language", "fa fa-laptop", "fa fa-leaf", "fa fa-gavel", "fa fa-lemon-o", "fa fa-level-down", "fa fa-level-up", "fa fa-life-ring", "fa fa-life-ring", "fa fa-life-ring", "fa fa-life-ring", "fa fa-lightbulb-o", "fa fa-line-chart", "fa fa-location-arrow", "fa fa-lock", "fa fa-low-vision"], ["fa fa-magic", "fa fa-magnet", "fa fa-share", "fa fa-reply", "fa fa-reply-all", "fa fa-male", "fa fa-map", "fa fa-map-marker", "fa fa-map-o", "fa fa-map-pin", "fa fa-map-signs", "fa fa-meh-o", "fa fa-microchip", "fa fa-microphone", "fa fa-microphone-slash", "fa fa-minus", "fa fa-minus-circle", "fa fa-minus-square", "fa fa-minus-square-o", "fa fa-mobile"], ["fa fa-mobile", "fa fa-money", "fa fa-moon-o", "fa fa-graduation-cap", "fa fa-motorcycle", "fa fa-mouse-pointer", "fa fa-music", "fa fa-bars", "fa fa-newspaper-o", "fa fa-object-group", "fa fa-object-ungroup", "fa fa-paint-brush", "fa fa-paper-plane", "fa fa-paper-plane-o", "fa fa-paw", "fa fa-pencil", "fa fa-pencil-square", "fa fa-pencil-square-o", "fa fa-percent", "fa fa-phone"], ["fa fa-phone-square", "fa fa-picture-o", "fa fa-picture-o", "fa fa-pie-chart", "fa fa-plane", "fa fa-plug", "fa fa-plus", "fa fa-plus-circle", "fa fa-plus-square", "fa fa-plus-square-o", "fa fa-podcast", "fa fa-power-off", "fa fa-print", "fa fa-puzzle-piece", "fa fa-qrcode", "fa fa-question", "fa fa-question-circle", "fa fa-question-circle-o", "fa fa-quote-left", "fa fa-quote-right"], ["fa fa-random", "fa fa-recycle", "fa fa-refresh", "fa fa-registered", "fa fa-times", "fa fa-bars", "fa fa-reply", "fa fa-reply-all", "fa fa-retweet", "fa fa-road", "fa fa-rocket", "fa fa-rss", "fa fa-rss-square", "fa fa-bath", "fa fa-search", "fa fa-search-minus", "fa fa-search-plus", "fa fa-paper-plane", "fa fa-paper-plane-o", "fa fa-server"], ["fa fa-share", "fa fa-share-alt", "fa fa-share-alt-square", "fa fa-share-square", "fa fa-share-square-o", "fa fa-shield", "fa fa-ship", "fa fa-shopping-bag", "fa fa-shopping-basket", "fa fa-shopping-cart", "fa fa-shower", "fa fa-sign-in", "fa fa-sign-language", "fa fa-sign-out", "fa fa-signal", "fa fa-sign-language", "fa fa-sitemap", "fa fa-sliders", "fa fa-smile-o", "fa fa-snowflake-o"], ["fa fa-futbol-o", "fa fa-sort", "fa fa-sort-alpha-asc", "fa fa-sort-alpha-desc", "fa fa-sort-amount-asc", "fa fa-sort-amount-desc", "fa fa-sort-asc", "fa fa-sort-desc", "fa fa-sort-desc", "fa fa-sort-numeric-asc", "fa fa-sort-numeric-desc", "fa fa-sort-asc", "fa fa-space-shuttle", "fa fa-spinner", "fa fa-spoon", "fa fa-square", "fa fa-square-o", "fa fa-star", "fa fa-star-half", "fa fa-star-half-o"], ["fa fa-star-half-o", "fa fa-star-half-o", "fa fa-star-o", "fa fa-sticky-note", "fa fa-sticky-note-o", "fa fa-street-view", "fa fa-suitcase", "fa fa-sun-o", "fa fa-life-ring", "fa fa-tablet", "fa fa-tachometer", "fa fa-tag", "fa fa-tags", "fa fa-tasks", "fa fa-taxi", "fa fa-television", "fa fa-terminal", "fa fa-thermometer-full", "fa fa-thermometer-empty", "fa fa-thermometer-quarter"], ["fa fa-thermometer-half", "fa fa-thermometer-three-quarters", "fa fa-thermometer-full", "fa fa-thermometer-empty", "fa fa-thermometer-full", "fa fa-thermometer-half", "fa fa-thermometer-quarter", "fa fa-thermometer-three-quarters", "fa fa-thumb-tack", "fa fa-thumbs-down", "fa fa-thumbs-o-down", "fa fa-thumbs-o-up", "fa fa-thumbs-up", "fa fa-ticket", "fa fa-times", "fa fa-times-circle", "fa fa-times-circle-o", "fa fa-window-close", "fa fa-window-close-o", "fa fa-tint"], ["fa fa-caret-square-o-down", "fa fa-caret-square-o-left", "fa fa-toggle-off", "fa fa-toggle-on", "fa fa-caret-square-o-right", "fa fa-caret-square-o-up", "fa fa-trademark", "fa fa-trash", "fa fa-trash-o", "fa fa-tree", "fa fa-trophy", "fa fa-truck", "fa fa-tty", "fa fa-television", "fa fa-umbrella", "fa fa-universal-access", "fa fa-university", "fa fa-unlock", "fa fa-unlock-alt", "fa fa-sort"], ["fa fa-upload", "fa fa-user", "fa fa-user-circle", "fa fa-user-circle-o", "fa fa-user-o", "fa fa-user-plus", "fa fa-user-secret", "fa fa-user-times", "fa fa-users", "fa fa-address-card", "fa fa-address-card-o", "fa fa-video-camera", "fa fa-volume-control-phone", "fa fa-volume-down", "fa fa-volume-off", "fa fa-volume-up", "fa fa-exclamation-triangle", "fa fa-wheelchair", "fa fa-wheelchair-alt", "fa fa-wifi"], ["fa fa-window-close", "fa fa-window-close-o", "fa fa-window-maximize", "fa fa-window-minimize", "fa fa-window-restore", "fa fa-wrench", "fa fa-american-sign-language-interpreting", "fa fa-american-sign-language-interpreting", "fa fa-assistive-listening-systems", "fa fa-audio-description", "fa fa-blind", "fa fa-braille", "fa fa-cc", "fa fa-deaf", "fa fa-deaf", "fa fa-deaf", "fa fa-low-vision", "fa fa-question-circle-o", "fa fa-sign-language", "fa fa-sign-language"], ["fa fa-tty", "fa fa-universal-access", "fa fa-volume-control-phone", "fa fa-wheelchair", "fa fa-wheelchair-alt", "fa fa-hand-rock-o", "fa fa-hand-lizard-o", "fa fa-hand-o-down", "fa fa-hand-o-left", "fa fa-hand-o-right", "fa fa-hand-o-up", "fa fa-hand-paper-o", "fa fa-hand-peace-o", "fa fa-hand-pointer-o", "fa fa-hand-rock-o", "fa fa-hand-scissors-o", "fa fa-hand-spock-o", "fa fa-hand-paper-o", "fa fa-thumbs-down", "fa fa-thumbs-o-down"], ["fa fa-thumbs-o-up", "fa fa-thumbs-up", "fa fa-ambulance", "fa fa-car", "fa fa-bicycle", "fa fa-bus", "fa fa-taxi", "fa fa-car", "fa fa-fighter-jet", "fa fa-motorcycle", "fa fa-plane", "fa fa-rocket", "fa fa-ship", "fa fa-space-shuttle", "fa fa-subway", "fa fa-taxi", "fa fa-train", "fa fa-truck", "fa fa-wheelchair", "fa fa-wheelchair-alt"], ["fa fa-genderless", "fa fa-transgender", "fa fa-mars", "fa fa-mars-double", "fa fa-mars-stroke", "fa fa-mars-stroke-h", "fa fa-mars-stroke-v", "fa fa-mercury", "fa fa-neuter", "fa fa-transgender", "fa fa-transgender-alt", "fa fa-venus", "fa fa-venus-double", "fa fa-venus-mars", "fa fa-file", "fa fa-file-archive-o", "fa fa-file-audio-o", "fa fa-file-code-o", "fa fa-file-excel-o", "fa fa-file-image-o"], ["fa fa-file-video-o", "fa fa-file-o", "fa fa-file-pdf-o", "fa fa-file-image-o", "fa fa-file-image-o", "fa fa-file-powerpoint-o", "fa fa-file-audio-o", "fa fa-file-text", "fa fa-file-text-o", "fa fa-file-video-o", "fa fa-file-word-o", "fa fa-file-archive-o", "fa fa-circle-o-notch", "fa fa-cog", "fa fa-cog", "fa fa-refresh", "fa fa-spinner", "fa fa-check-square", "fa fa-check-square-o", "fa fa-circle"], ["fa fa-circle-o", "fa fa-dot-circle-o", "fa fa-minus-square", "fa fa-minus-square-o", "fa fa-plus-square", "fa fa-plus-square-o", "fa fa-square", "fa fa-square-o", "fa fa-cc-amex", "fa fa-cc-diners-club", "fa fa-cc-discover", "fa fa-cc-jcb", "fa fa-cc-mastercard", "fa fa-cc-paypal", "fa fa-cc-stripe", "fa fa-cc-visa", "fa fa-credit-card", "fa fa-credit-card-alt", "fa fa-google-wallet", "fa fa-paypal"], ["fa fa-area-chart", "fa fa-bar-chart", "fa fa-bar-chart", "fa fa-line-chart", "fa fa-pie-chart", "fa fa-btc", "fa fa-btc", "fa fa-jpy", "fa fa-usd", "fa fa-eur", "fa fa-eur", "fa fa-gbp", "fa fa-gg", "fa fa-gg-circle", "fa fa-ils", "fa fa-inr", "fa fa-jpy", "fa fa-krw", "fa fa-money", "fa fa-jpy"], ["fa fa-rub", "fa fa-rub", "fa fa-rub", "fa fa-inr", "fa fa-ils", "fa fa-ils", "fa fa-try", "fa fa-try", "fa fa-usd", "fa fa-viacoin", "fa fa-krw", "fa fa-jpy", "fa fa-align-center", "fa fa-align-justify", "fa fa-align-left", "fa fa-align-right", "fa fa-bold", "fa fa-link", "fa fa-chain-broken", "fa fa-clipboard"], ["fa fa-columns", "fa fa-files-o", "fa fa-scissors", "fa fa-outdent", "fa fa-eraser", "fa fa-file", "fa fa-file-o", "fa fa-file-text", "fa fa-file-text-o", "fa fa-files-o", "fa fa-floppy-o", "fa fa-font", "fa fa-header", "fa fa-indent", "fa fa-italic", "fa fa-link", "fa fa-list", "fa fa-list-alt", "fa fa-list-ol", "fa fa-list-ul"], ["fa fa-outdent", "fa fa-paperclip", "fa fa-paragraph", "fa fa-clipboard", "fa fa-repeat", "fa fa-undo", "fa fa-repeat", "fa fa-floppy-o", "fa fa-scissors", "fa fa-strikethrough", "fa fa-subscript", "fa fa-superscript", "fa fa-table", "fa fa-text-height", "fa fa-text-width", "fa fa-th", "fa fa-th-large", "fa fa-th-list", "fa fa-underline", "fa fa-undo"], ["fa fa-chain-broken", "fa fa-angle-double-down", "fa fa-angle-double-left", "fa fa-angle-double-right", "fa fa-angle-double-up", "fa fa-angle-down", "fa fa-angle-left", "fa fa-angle-right", "fa fa-angle-up", "fa fa-arrow-circle-down", "fa fa-arrow-circle-left", "fa fa-arrow-circle-o-down", "fa fa-arrow-circle-o-left", "fa fa-arrow-circle-o-right", "fa fa-arrow-circle-o-up", "fa fa-arrow-circle-right", "fa fa-arrow-circle-up", "fa fa-arrow-down", "fa fa-arrow-left", "fa fa-arrow-right"], ["fa fa-arrow-up", "fa fa-arrows", "fa fa-arrows-alt", "fa fa-arrows-h", "fa fa-arrows-v", "fa fa-caret-down", "fa fa-caret-left", "fa fa-caret-right", "fa fa-caret-square-o-down", "fa fa-caret-square-o-left", "fa fa-caret-square-o-right", "fa fa-caret-square-o-up", "fa fa-caret-up", "fa fa-chevron-circle-down", "fa fa-chevron-circle-left", "fa fa-chevron-circle-right", "fa fa-chevron-circle-up", "fa fa-chevron-down", "fa fa-chevron-left", "fa fa-chevron-right"], ["fa fa-chevron-up", "fa fa-exchange", "fa fa-hand-o-down", "fa fa-hand-o-left", "fa fa-hand-o-right", "fa fa-hand-o-up", "fa fa-long-arrow-down", "fa fa-long-arrow-left", "fa fa-long-arrow-right", "fa fa-long-arrow-up", "fa fa-caret-square-o-down", "fa fa-caret-square-o-left", "fa fa-caret-square-o-right", "fa fa-caret-square-o-up", "fa fa-arrows-alt", "fa fa-backward", "fa fa-compress", "fa fa-eject", "fa fa-expand", "fa fa-fast-backward"], ["fa fa-fast-forward", "fa fa-forward", "fa fa-pause", "fa fa-pause-circle", "fa fa-pause-circle-o", "fa fa-play", "fa fa-play-circle", "fa fa-play-circle-o", "fa fa-random", "fa fa-step-backward", "fa fa-step-forward", "fa fa-stop", "fa fa-stop-circle", "fa fa-stop-circle-o", "fa fa-youtube-play", "fa fa-500px", "fa fa-adn", "fa fa-amazon", "fa fa-android", "fa fa-angellist"], ["fa fa-apple", "fa fa-bandcamp", "fa fa-behance", "fa fa-behance-square", "fa fa-bitbucket", "fa fa-bitbucket-square", "fa fa-btc", "fa fa-black-tie", "fa fa-bluetooth", "fa fa-bluetooth-b", "fa fa-btc", "fa fa-buysellads", "fa fa-cc-amex", "fa fa-cc-diners-club", "fa fa-cc-discover", "fa fa-cc-jcb", "fa fa-cc-mastercard", "fa fa-cc-paypal", "fa fa-cc-stripe", "fa fa-cc-visa"], ["fa fa-chrome", "fa fa-codepen", "fa fa-codiepie", "fa fa-connectdevelop", "fa fa-contao", "fa fa-css3", "fa fa-dashcube", "fa fa-delicious", "fa fa-deviantart", "fa fa-digg", "fa fa-dribbble", "fa fa-dropbox", "fa fa-drupal", "fa fa-edge", "fa fa-eercast", "fa fa-empire", "fa fa-envira", "fa fa-etsy", "fa fa-expeditedssl", "fa fa-font-awesome"], ["fa fa-facebook", "fa fa-facebook", "fa fa-facebook-official", "fa fa-facebook-square", "fa fa-firefox", "fa fa-first-order", "fa fa-flickr", "fa fa-font-awesome", "fa fa-fonticons", "fa fa-fort-awesome", "fa fa-forumbee", "fa fa-foursquare", "fa fa-free-code-camp", "fa fa-empire", "fa fa-get-pocket", "fa fa-gg", "fa fa-gg-circle", "fa fa-git", "fa fa-git-square", "fa fa-github"], ["fa fa-github-alt", "fa fa-github-square", "fa fa-gitlab", "fa fa-gratipay", "fa fa-glide", "fa fa-glide-g", "fa fa-google", "fa fa-google-plus", "fa fa-google-plus-official", "fa fa-google-plus-official", "fa fa-google-plus-square", "fa fa-google-wallet", "fa fa-gratipay", "fa fa-grav", "fa fa-hacker-news", "fa fa-houzz", "fa fa-html5", "fa fa-imdb", "fa fa-instagram", "fa fa-internet-explorer"], ["fa fa-ioxhost", "fa fa-joomla", "fa fa-jsfiddle", "fa fa-lastfm", "fa fa-lastfm-square", "fa fa-leanpub", "fa fa-linkedin", "fa fa-linkedin-square", "fa fa-linode", "fa fa-linux", "fa fa-maxcdn", "fa fa-meanpath", "fa fa-medium", "fa fa-meetup", "fa fa-mixcloud", "fa fa-modx", "fa fa-odnoklassniki", "fa fa-odnoklassniki-square", "fa fa-opencart", "fa fa-openid"], ["fa fa-opera", "fa fa-optin-monster", "fa fa-pagelines", "fa fa-paypal", "fa fa-pied-piper", "fa fa-pied-piper-alt", "fa fa-pied-piper-pp", "fa fa-pinterest", "fa fa-pinterest-p", "fa fa-pinterest-square", "fa fa-product-hunt", "fa fa-qq", "fa fa-quora", "fa fa-rebel", "fa fa-ravelry", "fa fa-rebel", "fa fa-reddit", "fa fa-reddit-alien", "fa fa-reddit-square", "fa fa-renren"], ["fa fa-rebel", "fa fa-safari", "fa fa-scribd", "fa fa-sellsy", "fa fa-share-alt", "fa fa-share-alt-square", "fa fa-shirtsinbulk", "fa fa-simplybuilt", "fa fa-skyatlas", "fa fa-skype", "fa fa-slack", "fa fa-slideshare", "fa fa-snapchat", "fa fa-snapchat-ghost", "fa fa-snapchat-square", "fa fa-soundcloud", "fa fa-spotify", "fa fa-stack-exchange", "fa fa-stack-overflow", "fa fa-steam"], ["fa fa-steam-square", "fa fa-stumbleupon", "fa fa-stumbleupon-circle", "fa fa-superpowers", "fa fa-telegram", "fa fa-tencent-weibo", "fa fa-themeisle", "fa fa-trello", "fa fa-tripadvisor", "fa fa-tumblr", "fa fa-tumblr-square", "fa fa-twitch", "fa fa-twitter", "fa fa-twitter-square", "fa fa-usb", "fa fa-viacoin", "fa fa-viadeo", "fa fa-viadeo-square", "fa fa-vimeo", "fa fa-vimeo-square"], ["fa fa-vine", "fa fa-vk", "fa fa-weixin", "fa fa-weibo", "fa fa-weixin", "fa fa-whatsapp", "fa fa-wikipedia-w", "fa fa-windows", "fa fa-wordpress", "fa fa-wpbeginner", "fa fa-wpexplorer", "fa fa-wpforms", "fa fa-xing", "fa fa-xing-square", "fa fa-y-combinator", "fa fa-hacker-news", "fa fa-yahoo", "fa fa-y-combinator", "fa fa-hacker-news", "fa fa-yelp"], ["fa fa-yoast", "fa fa-youtube", "fa fa-youtube-play", "fa fa-youtube-square", "fa fa-ambulance", "fa fa-h-square", "fa fa-heart", "fa fa-heart-o", "fa fa-heartbeat", "fa fa-hospital-o", "fa fa-medkit", "fa fa-plus-square", "fa fa-stethoscope", "fa fa-user-md", "fa fa-wheelchair", "fa fa-wheelchair-alt"]
];
var bootstrap4TemplateContents = {
    "cards/3_users": "<div class=\"card-deck\">\r\n    <div class=\"card\">\r\n        <img class=\"card-img-top\" src=\"{{image.avatar}}\" alt=\"Card image cap\">\r\n        <div class=\"card-body\">\r\n            <h5 class=\"card-title\">{{name.findName}}</h5>\r\n            <p class=\"card-text\">{{name.jobTitle}}</p>\r\n            <p class=\"card-text\"><small class=\"text-muted\">Last updated 3 mins ago</small></p>\r\n        </div>\r\n    </div>\r\n    <div class=\"card\">\r\n        <img class=\"card-img-top\" src=\"{{image.avatar}}\" alt=\"Card image cap\">\r\n        <div class=\"card-body\">\r\n            <h5 class=\"card-title\">{{name.findName}}</h5>\r\n            <p class=\"card-text\">{{name.jobTitle}}</p>\r\n            <p class=\"card-text\"><small class=\"text-muted\">Last updated 3 mins ago</small></p>\r\n        </div>\r\n    </div>\r\n    <div class=\"card\">\r\n        <img class=\"card-img-top\" src=\"{{image.avatar}}\" alt=\"Card image cap\">\r\n        <div class=\"card-body\">\r\n            <h5 class=\"card-title\">{{name.findName}}</h5>\r\n            <p class=\"card-text\">{{name.jobTitle}}</p>\r\n            <p class=\"card-text\"><small class=\"text-muted\">Last updated 3 mins ago</small></p>\r\n        </div>\r\n    </div>\r\n</div>\r\n",
    "cards/4_products": "<div class=\"card-deck\">\r\n    <div class=\"card\">\r\n        <img class=\"card-img-top\" src=\"{{image.abstract}}\" alt=\"Card image cap\">\r\n        <div class=\"card-body\">\r\n            <h5 class=\"card-title\">\r\n                {{commerce.productName}}\r\n                <label class=\"badge badge-primary badge-pill\">${{commerce.price}}</label>\r\n            </h5>\r\n            <p class=\"card-text\">This is a longer card with supporting text below as a natural lead-in to additional\r\n                content. This content is a little bit longer.</p>\r\n            <p class=\"card-text\">\r\n                <label class=\"badge badge-secondary\">{{commerce.productAdjective}}</label>\r\n                <small class=\"text-muted\">Last updated 3 mins ago</small>\r\n            </p>\r\n        </div>\r\n    </div>\r\n    <div class=\"card\">\r\n        <img class=\"card-img-top\" src=\"{{image.abstract}}\" alt=\"Card image cap\">\r\n        <div class=\"card-body\">\r\n            <h5 class=\"card-title\">\r\n                {{commerce.productName}}\r\n                <label class=\"badge badge-primary badge-pill\">${{commerce.price}}</label>\r\n            </h5>\r\n            <p class=\"card-text\">This card has supporting text below as a natural lead-in to additional content.</p>\r\n            <p class=\"card-text\">\r\n                <label class=\"badge badge-secondary\">{{commerce.productAdjective}}</label>\r\n                <small class=\"text-muted\">Last updated 3 mins ago</small>\r\n            </p>\r\n        </div>\r\n    </div>\r\n    <div class=\"card\">\r\n        <img class=\"card-img-top\" src=\"{{image.abstract}}\" alt=\"Card image cap\">\r\n        <div class=\"card-body\">\r\n            <h5 class=\"card-title\">\r\n                {{commerce.productName}}\r\n                <label class=\"badge badge-primary badge-pill\">${{commerce.price}}</label>\r\n            </h5>\r\n            <p class=\"card-text\">This is a wider card with supporting text below as a natural lead-in to additional\r\n                content. This card has even longer content than the first to show that equal height action.</p>\r\n            <p class=\"card-text\">\r\n                <label class=\"badge badge-secondary\">{{commerce.productAdjective}}</label>\r\n                <small class=\"text-muted\">Last updated 3 mins ago</small>\r\n            </p>\r\n        </div>\r\n    </div>\r\n    <div class=\"card\">\r\n        <img class=\"card-img-top\" src=\"{{image.abstract}}\" alt=\"Card image cap\">\r\n        <div class=\"card-body\">\r\n            <h5 class=\"card-title\">\r\n                {{commerce.productName}}\r\n                <label class=\"badge badge-primary badge-pill\">${{commerce.price}}</label>\r\n            </h5>\r\n            <p class=\"card-text\">This is a wider card with supporting text below as a natural lead-in to additional\r\n                content. This card has even longer content than the first to show that equal height action.</p>\r\n            <p class=\"card-text\">\r\n                <label class=\"badge badge-secondary\">{{commerce.productAdjective}}</label>\r\n                <small class=\"text-muted\">Last updated 3 mins ago</small>\r\n            </p>\r\n        </div>\r\n    </div>\r\n</div>\r\n",
    "cards/header_footer": "\r\n\r\n<div class=\"card mb-3\">\r\n    <div class=\"card-header\">{{lorem.sentence}}</div>\r\n    <div class=\"card-body\">\r\n        <h5 class=\"card-title\">{{lorem.sentence}}</h5>\r\n        <p class=\"card-text\">{{lorem.sentences}}</p>\r\n    </div>\r\n    <div class=\"card-footer\">\r\n        <p class=\"card-text\"><small class=\"text-muted\">Last updated 3 mins ago</small></p>\r\n    </div>\r\n</div>\r\n\r\n",
    "cards/image_title": "\r\n\r\n<div class=\"card\">\r\n    <img class=\"card-img-top\" src=\"{{image.imageUrl}}\" alt=\"Card image cap\">\r\n\r\n    <div class=\"card-body\">\r\n        <h5 class=\"card-title\">{{lorem.sentence}}</h5>\r\n\r\n        <p class=\"card-text\">{{lorem.paragraph}}</p>\r\n        <p class=\"card-text\"><small class=\"text-muted\">Last updated 3 mins ago</small></p>\r\n\r\n    </div>\r\n</div>\r\n\r\n",
    "cards/title_link": "\r\n<div class=\"card\">\r\n    <div class=\"card-body\">\r\n        <h5 class=\"card-title\">{{lorem.sentence}}</h5>\r\n        <p class=\"card-text\">{{lorem.sentences}}</p>\r\n        <a href=\"#\" class=\"btn btn-primary\">Button</a>\r\n    </div>\r\n</div>\r\n\r\n",
    "components/blockquote": "\r\n<blockquote class=\"blockquote text-right\">\r\n    <p class=\"mb-0\">{{lorem.sentence}}</p>\r\n    <footer class=\"blockquote-footer\">{{name.findName}} in <cite title=\"Source Title\">{{name.jobArea}}</cite></footer>\r\n</blockquote>\r\n",
    "components/carousel": "\r\n\r\n<div id=\"carouselExampleIndicators\" class=\"carousel slide\" data-ride=\"carousel\">\r\n    <ol class=\"carousel-indicators\">\r\n        <li data-target=\"#carouselExampleIndicators\" data-slide-to=\"0\" class=\"active\"></li>\r\n        <li data-target=\"#carouselExampleIndicators\" data-slide-to=\"1\"></li>\r\n        <li data-target=\"#carouselExampleIndicators\" data-slide-to=\"2\"></li>\r\n    </ol>\r\n    <div class=\"carousel-inner\">\r\n        <div class=\"carousel-item active\">\r\n            <img class=\"d-block w-100\" src=\"/images/carousel/desert.jpg\" alt=\"First slide\">\r\n            <div class=\"carousel-caption d-none d-md-block\">\r\n                <h5>{{lorem.words}}</h5>\r\n                <p>{{lorem.sentence}}</p>\r\n            </div>\r\n        </div>\r\n        <div class=\"carousel-item\">\r\n            <img class=\"d-block w-100\" src=\"/images/carousel/penguins.jpg\" alt=\"Second slide\">\r\n            <div class=\"carousel-caption d-none d-md-block\">\r\n                <h5>{{lorem.words}}</h5>\r\n                <p>{{lorem.sentence}}</p>\r\n            </div>\r\n        </div>\r\n        <div class=\"carousel-item\">\r\n            <img class=\"d-block w-100\" src=\"/images/carousel/tulips.jpg\" alt=\"Third slide\">\r\n            <div class=\"carousel-caption d-none d-md-block\">\r\n                <h5>{{lorem.words}}</h5>\r\n                <p>{{lorem.sentence}}</p>\r\n            </div>\r\n        </div>\r\n    </div>\r\n    <a class=\"carousel-control-prev\" href=\"#carouselExampleIndicators\" role=\"button\" data-slide=\"prev\">\r\n        <span class=\"carousel-control-prev-icon\" aria-hidden=\"true\"></span>\r\n        <span class=\"sr-only\">Previous</span>\r\n    </a>\r\n    <a class=\"carousel-control-next\" href=\"#carouselExampleIndicators\" role=\"button\" data-slide=\"next\">\r\n        <span class=\"carousel-control-next-icon\" aria-hidden=\"true\"></span>\r\n        <span class=\"sr-only\">Next</span>\r\n    </a>\r\n</div>\r\n\r\n\r\n\r\n",
    "components/chatbox": "<div class=\"row pt-3\">\r\n            <div class=\"chat-main\">\r\n                <div class=\"col-md-12 chat-header rounded-top bg-primary text-white\">\r\n                    <div class=\"row\">\r\n                        <div class=\"col-md-6 username pl-2\">\r\n                            <i class=\"fa fa-circle text-success\" aria-hidden=\"true\"></i>\r\n                            <h6 class=\"m-0\">Adam Finn</h6>\r\n                        </div>\r\n                        <div class=\"col-md-6 options text-right pr-2\">\r\n                            <i class=\"fa fa-plus mr-2\" aria-hidden=\"true\"></i>\r\n                            <i class=\"fa fa-video-camera\" aria-hidden=\"true\"></i>\r\n                            <i class=\"fa fa-circle text-success live-video mr-1\" aria-hidden=\"true\"></i>\r\n                            <i class=\"fa fa-phone mr-2\" aria-hidden=\"true\"></i>\r\n                            <i class=\"fa fa-cog mr-2\" aria-hidden=\"true\"></i>\r\n                            <i class=\"fa fa-times hide-chat-box\" aria-hidden=\"true\"></i>\r\n                          </div>\r\n                    </div>\r\n                </div>\r\n                <div class=\"chat-content\">\r\n                    <div class=\"col-md-12 chats border\">\r\n                        <ul class=\"p-0\">\r\n                            <li class=\"pl-2 pr-2 bg-primary rounded text-white text-center send-msg mb-1\">\r\n                                hiii\r\n                            </li>\r\n                            <li class=\"p-1 rounded mb-1\">\r\n                                <div class=\"receive-msg\">\r\n                                    <img src=\"http://nicesnippets.com/demo/image1.jpg\">\r\n                                    <div class=\"receive-msg-desc  text-center mt-1 ml-1 pl-2 pr-2\">\r\n                                        <p class=\"pl-2 pr-2 rounded\">hello</p>\r\n                                    </div>\r\n                                </div>\r\n                            </li>\r\n                            <li class=\"pl-2 pr-2 bg-primary rounded text-white text-center send-msg mb-1\">\r\n                                how are you ???\r\n                            </li>\r\n                            <li class=\"p-1 rounded mb-1\">\r\n                                <div class=\"receive-msg\">\r\n                                    <div class=\"receive-msg-img\">\r\n                                        <img src=\"http://nicesnippets.com/demo/image1.jpg\">\r\n                                    </div>\r\n                                    <div class=\"receive-msg-desc rounded text-center mt-1 ml-1 pl-2 pr-2\">\r\n                                        <p class=\"mb-0 mt-1 pl-2 pr-2 rounded-top rounded-right\">I'm fine !!!</p>\r\n                                        <p class=\"mb-0 mt-1 pl-2 pr-2 rounded-bottom rounded-right\">Where are you ?</p>\r\n                                    </div>\r\n                                </div>\r\n                            </li>\r\n                            <li class=\"pl-2 pr-2 bg-primary text-white text-center send-msg mb-1 rounded-top rounded-left\">\r\n                                at california\r\n                            </li>\r\n                            <li class=\"pl-2 pr-2 bg-primary text-white text-center send-msg mb-1 rounded-bottom rounded-left\">\r\n                                and where are you ?\r\n                            </li>\r\n                            <li class=\"p-1 rounded  mb-1\">\r\n                                <div class=\"receive-msg\">\r\n                                    <img src=\"http://nicesnippets.com/demo/image1.jpg\">\r\n                                    <div class=\"receive-msg-desc rounded text-center mt-1 ml-1 pl-2 pr-2\">\r\n                                        <p class=\"pl-2 pr-2 rounded\">now i'm at new york city</p>\r\n                                    </div>\r\n                                </div>\r\n                            </li>\r\n                            <li class=\"pl-2 pr-2 bg-primary rounded text-white text-center send-msg mb-1\">\r\n                                Ok\r\n                            </li>\r\n                        </ul>\r\n                    </div>\r\n                    <div class=\"col-md-12 message-box border pl-2 pr-2 border-top-0\">\r\n                        <input type=\"text\" class=\"pl-0 pr-0 w-100\" placeholder=\"Type a message...\">\r\n                        <div class=\"tools\">\r\n                            <i class=\"fa fa-picture-o\" aria-hidden=\"true\"></i>\r\n                            <i class=\"fa fa-telegram\" aria-hidden=\"true\"></i>\r\n                            <i class=\"fa fa-bell\" aria-hidden=\"true\"></i>\r\n                            <i class=\"fa fa-meh-o\" aria-hidden=\"true\"></i>\r\n                            <i class=\"fa fa-paperclip\" aria-hidden=\"true\"></i>\r\n                            <i class=\"fa fa-gamepad\" aria-hidden=\"true\"></i>\r\n                            <i class=\"fa fa-camera\" aria-hidden=\"true\"></i>\r\n                            <i class=\"fa fa-folder\" aria-hidden=\"true\"></i>\r\n                            <i class=\"fa fa-thumbs-o-up m-0\" aria-hidden=\"true\"></i>\r\n                        </div>\r\n                    </div>\r\n                </div>\r\n            </div>\r\n        </div>",
    "components/collapse": "\r\n\r\n<div id=\"accordion\">\r\n    <div class=\"card\">\r\n        <div class=\"card-header\" id=\"headingOne\">\r\n            <h5 class=\"mb-0\">\r\n                <button class=\"btn btn-link\" data-toggle=\"collapse\" data-target=\"#collapseOne\" aria-expanded=\"true\"\r\n                        aria-controls=\"collapseOne\">\r\n                    {{lorem.sentence}}\r\n                </button>\r\n            </h5>\r\n        </div>\r\n\r\n        <div id=\"collapseOne\" class=\"collapse show\" aria-labelledby=\"headingOne\" data-parent=\"#accordion\">\r\n            <div class=\"card-body\">\r\n                {{lorem.paragraph}}\r\n            </div>\r\n        </div>\r\n    </div>\r\n    <div class=\"card\">\r\n        <div class=\"card-header\" id=\"headingTwo\">\r\n            <h5 class=\"mb-0\">\r\n                <button class=\"btn btn-link collapsed\" data-toggle=\"collapse\" data-target=\"#collapseTwo\"\r\n                        aria-expanded=\"false\" aria-controls=\"collapseTwo\">\r\n                    {{lorem.sentence}}\r\n                </button>\r\n            </h5>\r\n        </div>\r\n        <div id=\"collapseTwo\" class=\"collapse\" aria-labelledby=\"headingTwo\" data-parent=\"#accordion\">\r\n            <div class=\"card-body\">\r\n                {{lorem.paragraph}}\r\n            </div>\r\n        </div>\r\n    </div>\r\n    <div class=\"card\">\r\n        <div class=\"card-header\" id=\"headingThree\">\r\n            <h5 class=\"mb-0\">\r\n                <button class=\"btn btn-link collapsed\" data-toggle=\"collapse\" data-target=\"#collapseThree\"\r\n                        aria-expanded=\"false\" aria-controls=\"collapseThree\">\r\n                    {{lorem.sentence}}\r\n                </button>\r\n            </h5>\r\n        </div>\r\n        <div id=\"collapseThree\" class=\"collapse\" aria-labelledby=\"headingThree\" data-parent=\"#accordion\">\r\n            <div class=\"card-body\">\r\n                {{lorem.paragraph}}\r\n            </div>\r\n        </div>\r\n    </div>\r\n</div>\r\n\r\n\r\n",
    "components/dropdown": "\r\n\r\n<div class=\"dropdown\">\r\n    <button class=\"btn btn-secondary dropdown-toggle\" type=\"button\" id=\"dropdownMenu2\" data-toggle=\"dropdown\"\r\n            aria-haspopup=\"true\" aria-expanded=\"false\">\r\n        {{lorem.word}}\r\n    </button>\r\n    <div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenu2\">\r\n        <button class=\"dropdown-item\" type=\"button\">{{lorem.word}}</button>\r\n        <button class=\"dropdown-item\" type=\"button\">{{lorem.word}}</button>\r\n        <div class=\"dropdown-divider\"></div>\r\n        <button class=\"dropdown-item\" type=\"button\">{{lorem.word}}</button>\r\n    </div>\r\n</div>\r\n\r\n",
    "components/jumbotron": "\r\n\r\n<div class=\"jumbotron\">\r\n    <h1 class=\"display-4\">Hello, world!</h1>\r\n    <p class=\"lead\">This is a simple hero unit, a simple jumbotron-style component for calling extra attention to\r\n        featured content or information.</p>\r\n    <hr class=\"my-4\">\r\n    <p>It uses utility classes for typography and spacing to space content out within the larger container.</p>\r\n    <p class=\"lead\">\r\n        <a class=\"btn btn-primary btn-lg\" href=\"#\" role=\"button\">Learn more</a>\r\n    </p>\r\n</div>\r\n\r\n",
    "components/list_group": "\r\n\r\n<ul class=\"list-group\">\r\n    <li class=\"list-group-item\">{{lorem.sentence}}</li>\r\n    <li class=\"list-group-item\">{{lorem.sentence}}</li>\r\n    <li class=\"list-group-item\">{{lorem.sentence}}</li>\r\n    <li class=\"list-group-item\">{{lorem.sentence}}</li>\r\n    <li class=\"list-group-item\">{{lorem.sentence}}</li>\r\n</ul>\r\n\r\n",
    "components/modal": "\r\n\r\n<!-- Button trigger modal -->\r\n<button type=\"button\" class=\"btn btn-primary\" data-toggle=\"modal\" data-target=\"#exampleModalCenter\">\r\n    Launch modal\r\n</button>\r\n\r\n<!-- Modal -->\r\n<div class=\"modal fade\" id=\"exampleModalCenter\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"exampleModalCenterTitle\"\r\n     aria-hidden=\"true\">\r\n    <div class=\"modal-dialog modal-dialog-centered\" role=\"document\">\r\n        <div class=\"modal-content\">\r\n            <div class=\"modal-header\">\r\n                <h5 class=\"modal-title\" id=\"exampleModalLongTitle\">Modal title</h5>\r\n                <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">\r\n                    <span aria-hidden=\"true\">&times;</span>\r\n                </button>\r\n            </div>\r\n            <div class=\"modal-body\">\r\n                Your text\r\n            </div>\r\n            <div class=\"modal-footer\">\r\n                <button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">Close</button>\r\n                <button type=\"button\" class=\"btn btn-primary\">Save</button>\r\n            </div>\r\n        </div>\r\n    </div>\r\n</div>\r\n\r\n",
    "components/navbar": "\r\n\r\n<nav class=\"navbar navbar-expand-lg navbar-light bg-light\">\r\n    <a class=\"navbar-brand\" href=\"#\">Navbar</a>\r\n    <button class=\"navbar-toggler\" type=\"button\" data-toggle=\"collapse\" data-target=\"#navbarNavDropdown\"\r\n            aria-controls=\"navbarNavDropdown\" aria-expanded=\"false\" aria-label=\"Toggle navigation\">\r\n        <span class=\"navbar-toggler-icon\"></span>\r\n    </button>\r\n    <div class=\"collapse navbar-collapse\" id=\"navbarNavDropdown\">\r\n        <ul class=\"navbar-nav\">\r\n            <li class=\"nav-item active\">\r\n                <a class=\"nav-link\" href=\"#\">Home <span class=\"sr-only\">(current)</span></a>\r\n            </li>\r\n            <li class=\"nav-item\">\r\n                <a class=\"nav-link\" href=\"#\">Features</a>\r\n            </li>\r\n            <li class=\"nav-item\">\r\n                <a class=\"nav-link\" href=\"#\">Pricing</a>\r\n            </li>\r\n            <li class=\"nav-item dropdown\">\r\n                <a class=\"nav-link dropdown-toggle\" href=\"#\" id=\"navbarDropdownMenuLink\" data-toggle=\"dropdown\"\r\n                   aria-haspopup=\"true\" aria-expanded=\"false\">\r\n                    Dropdown link\r\n                </a>\r\n                <div class=\"dropdown-menu\" aria-labelledby=\"navbarDropdownMenuLink\">\r\n                    <a class=\"dropdown-item\" href=\"#\">Action</a>\r\n                    <a class=\"dropdown-item\" href=\"#\">Another action</a>\r\n                    <a class=\"dropdown-item\" href=\"#\">Something else here</a>\r\n                </div>\r\n            </li>\r\n        </ul>\r\n    </div>\r\n</nav>\r\n\r\n",
    "components/nav_tabs": "\r\n\r\n<div>\r\n    <nav>\r\n        <div class=\"nav nav-tabs\" id=\"nav-tab\" role=\"tablist\">\r\n            <a class=\"nav-item nav-link active\" id=\"nav-home-tab\" data-toggle=\"tab\" href=\"#nav-home\" role=\"tab\"\r\n               aria-controls=\"nav-home\" aria-selected=\"true\">Home</a>\r\n            <a class=\"nav-item nav-link\" id=\"nav-profile-tab\" data-toggle=\"tab\" href=\"#nav-profile\" role=\"tab\"\r\n               aria-controls=\"nav-profile\" aria-selected=\"false\">Profile</a>\r\n            <a class=\"nav-item nav-link\" id=\"nav-contact-tab\" data-toggle=\"tab\" href=\"#nav-contact\" role=\"tab\"\r\n               aria-controls=\"nav-contact\" aria-selected=\"false\">Contact</a>\r\n        </div>\r\n    </nav>\r\n    <div class=\"tab-content\" id=\"nav-tabContent\">\r\n        <div class=\"tab-pane fade show active\" id=\"nav-home\" role=\"tabpanel\" aria-labelledby=\"nav-home-tab\">...</div>\r\n        <div class=\"tab-pane fade\" id=\"nav-profile\" role=\"tabpanel\" aria-labelledby=\"nav-profile-tab\">...</div>\r\n        <div class=\"tab-pane fade\" id=\"nav-contact\" role=\"tabpanel\" aria-labelledby=\"nav-contact-tab\">...</div>\r\n    </div>\r\n</div>\r\n\r\n",
    "components/pagination": "\r\n\r\n<nav aria-label=\"Page navigation example\">\r\n    <ul class=\"pagination\">\r\n        <li class=\"page-item\">\r\n            <a class=\"page-link\" href=\"#\" aria-label=\"Previous\">\r\n                <span aria-hidden=\"true\">&laquo;</span>\r\n                <span class=\"sr-only\">Previous</span>\r\n            </a>\r\n        </li>\r\n        <li class=\"page-item\"><a class=\"page-link\" href=\"#\">1</a></li>\r\n        <li class=\"page-item\"><a class=\"page-link\" href=\"#\">2</a></li>\r\n        <li class=\"page-item\"><a class=\"page-link\" href=\"#\">3</a></li>\r\n        <li class=\"page-item\">\r\n            <a class=\"page-link\" href=\"#\" aria-label=\"Next\">\r\n                <span aria-hidden=\"true\">&raquo;</span>\r\n                <span class=\"sr-only\">Next</span>\r\n            </a>\r\n        </li>\r\n    </ul>\r\n</nav>\r\n\r\n",
    "components/post": "<div class=\"row\">\r\n    <div class=\"col-lg-2 col-sm-2 col-5\">\r\n        <img src=\"https://picsum.photos/300/300?image=100\" class=\"img-thumbnail\" width=\"150px\">\r\n    </div>\r\n    <div class=\"col-lg-10 col-sm-10 col-7\">\r\n        <h4 class=\"text-primary\">{{lorem.sentence}}</h4>\r\n        <p>{{lorem.paragraphs}}&nbsp;&nbsp;<button class=\"btn btn-sm btn-default\">Read more</button>\r\n        </p>\r\n\r\n    </div>\r\n</div>\r\n<div class=\"row post-detail\">\r\n    <div class=\"col-lg-12 col-sm-12 col-12\">\r\n        <ul class=\"list-inline\">\r\n            <li class=\"list-inline-item\">\r\n                <img src=\"{{image.avatar}}\" class=\"rounded-circle\" width=\"20px\"> <span>by</span> <span class=\"text-info\">{{name.firstName}}</span>\r\n            </li>\r\n            <li class=\"list-inline-item\">\r\n                <i class=\"fa fa-calendar\" aria-hidden=\"true\"></i> <span>Sept 16th,2017</span>\r\n            </li>\r\n            <li class=\"list-inline-item\">\r\n                <i class=\"fa fa-comment\" aria-hidden=\"true\"></i> <span class=\"text-info\">{{random.number}} Comments</span>\r\n            </li>\r\n            <li class=\"list-inline-item\">\r\n                <i class=\"fa fa-share-square-o\" aria-hidden=\"true\"></i> <span class=\"text-info\">{{random.number}} Shares</span>&nbsp; <i class=\"fa fa-eye\"></i>&nbsp;{{random.number}}</li><li class=\"list-inline-item\">\r\n            <i class=\"fa fa-tags\" aria-hidden=\"true\"></i>\r\n            <span>Tags:</span>\r\n\r\n            <span class=\"badge badge-secondary\">{{lorem.word}}</span>\r\n            <span class=\"badge badge-secondary\">{{lorem.word}}</span>\r\n            <span class=\"badge badge-secondary\">{{lorem.word}}</span>&nbsp;&nbsp;<i class=\"fa fa-facebook-official\"></i>&nbsp;&nbsp;<i class=\"fa fa-twitter\"></i>&nbsp;&nbsp;</li></ul>\r\n    </div>\r\n</div>\r\n\r\n\r\n<hr>",
    "components/profile_banner": "<div class=\"row\">\r\n\t\t\t<div class=\"col-md-10 col-md-offset-1 col-md-1-offset profile-wrapper col-sm-12 col-xs-12\">\r\n\t\t\t\t<div class=\"row\">\r\n\t\t\t\t\t<div class=\"col-md-12 profile-back-img col-sm-12 col-xs-12\">\r\n\t\t\t\t\t\t<img src=\"https://picsum.photos/1100/300/?random\">\r\n\t\t\t\t\t</div>\r\n\t\t\t\t\t<div class=\"col-md-12 profile-name-area col-sm-12 col-xs-12\">\r\n\t\t\t\t\t\t<div class=\"row\">\r\n\t\t\t\t\t\t\t<div class=\"col-md-2 text-center user-pic col-sm-2 col-xs-12\">\r\n\t\t\t\t\t\t\t\t<img src=\"{{image.avatar}}\">\r\n\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t<div class=\"col-md-5 user-name col-sm-5 col-xs-12\">\r\n\t\t\t\t\t\t\t\t<h2><strong><a href=\"#\">{{name.findName}}</a></strong></h2>\r\n\t\t\t\t\t\t\t\t<p>16 followers</p>\r\n\t\t\t\t\t\t\t</div>\r\n\r\n\t\t\t\t\t\t\t<div class=\"col-md-5 profile-btn-area col-sm-5 col-xs-12\">\r\n\t\t\t\t\t\t\t<div class=\"pull-right\">\r\n\t\t\t\t\t\t\t\t<button class=\"edt-btn\"><strong>EDIT PROFILE</strong></button>\r\n\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t \r\n\t\t\t\t\t\t\t\t <div class=\"dropdown pull-right\">\r\n\t\t\t\t\t\t\t\t  <button class=\"dropdown-toggle toogle-btn\" data-toggle=\"dropdown\"><i class=\"fa fa-ellipsis-v\"></i>\r\n\t\t\t\t\t\t\t\t  </button>\r\n\t\t\t\t\t\t\t\t  <ul class=\"dropdown-menu pull-right\">\r\n\t\t\t\t\t\t\t\t    <li><a href=\"#\">HTML</a></li>\r\n\t\t\t\t\t\t\t\t    <li><a href=\"#\">CSS</a></li>\r\n\t\t\t\t\t\t\t\t    <li><a href=\"#\">JavaScript</a></li>\r\n\t\t\t\t\t\t\t\t  </ul>\r\n\t\t\t\t\t\t\t\t</div> \r\n\t\t\t\t\t\t\t\t<b><a href=\"#\" class=\"abt-btn pull-right\">ABOUT</a></b>\r\n\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t</div>\r\n\t\t\t\t\t</div>\r\n\t\t\t\t</div>\t\t\r\n\t\t\t</div>\r\n\t\t</div>",
    "components/rating": "<fieldset class=\"rating-star text-center pl-5 pb-5\">\r\n                <input type=\"radio\" id=\"star5\" name=\"rating\" value=\"5\"><label class=\"full\" for=\"star5\" title=\"Awesome - 5 stars\"></label>\r\n                <input type=\"radio\" id=\"star4half\" name=\"rating\" value=\"4 and a half\"><label class=\"half\" for=\"star4half\" title=\"Pretty good - 4.5 stars\"></label>\r\n                <input type=\"radio\" id=\"star4\" name=\"rating\" value=\"4\"><label class=\"full\" for=\"star4\" title=\"Pretty good - 4 stars\"></label>\r\n                <input type=\"radio\" id=\"star3half\" name=\"rating\" value=\"3 and a half\"><label class=\"half\" for=\"star3half\" title=\"Meh - 3.5 stars\"></label>\r\n                <input type=\"radio\" id=\"star3\" name=\"rating\" value=\"3\"><label class=\"full\" for=\"star3\" title=\"Meh - 3 stars\"></label>\r\n                <input type=\"radio\" id=\"star2half\" name=\"rating\" value=\"2 and a half\"><label class=\"half\" for=\"star2half\" title=\"Kinda bad - 2.5 stars\"></label>\r\n                <input type=\"radio\" id=\"star2\" name=\"rating\" value=\"2\"><label class=\"full\" for=\"star2\" title=\"Kinda bad - 2 stars\"></label>\r\n                <input type=\"radio\" id=\"star1half\" name=\"rating\" value=\"1 and a half\"><label class=\"half\" for=\"star1half\" title=\"Meh - 1.5 stars\"></label>\r\n                <input type=\"radio\" id=\"star1\" name=\"rating\" value=\"1\"><label class=\"full\" for=\"star1\" title=\"Sucks big time - 1 star\"></label>\r\n                <input type=\"radio\" id=\"starhalf\" name=\"rating\" value=\"half\"><label class=\"half\" for=\"starhalf\" title=\"Sucks big time - 0.5 stars\"></label>\r\n              </fieldset>",
    "components/round_users_box": "<div class=\"row\">\r\n        <div class=\"col-md-4 col-sm-6 col-xs-12\">\r\n            <section class=\"new-deal\">\r\n            <div class=\"item-slide\">\r\n              <div class=\"box-img\">\r\n                <img src=\"{{image.avatar}}\" alt=\"profile-img\">\r\n              </div>\r\n              <div class=\"row\">\r\n                <div class=\"col-md-10 slide-hover\">\r\n                  <h2>{{name.findName}}</h2>\r\n                  <p>{{name.jobTitle}}</p>\r\n                  <span>Lorem ipsum dolor sit amet,<br>\r\n                    consectetur adipisicing elit, sed do<br>\r\n                      eiusmod tempor.\r\n                  </span><br><br>\r\n                  <i class=\"fa fa-facebook\"></i>\r\n                  <i class=\"fa fa-twitter\"></i>\r\n                  <i class=\"fa fa-google\"></i>\r\n                  <i class=\"fa fa-youtube\"></i>\r\n                </div>\r\n              </div>\r\n            </div>\r\n          </section>        \r\n        </div>\r\n        <div class=\"col-md-4 col-sm-6 col-xs-12\">\r\n            <section class=\"new-deal\">\r\n            <div class=\"item-slide\">\r\n              <div class=\"box-img\">\r\n                <img src=\"{{image.avatar}}\" alt=\"profile-img\">\r\n              </div>\r\n              <div class=\"row\">\r\n                <div class=\"col-md-10 slide-hover\">\r\n                  <h2>{{name.findName}}</h2>\r\n                  <p>{{name.jobTitle}}</p>\r\n                  <span>Lorem ipsum dolor sit amet,<br>\r\n                    consectetur adipisicing elit, sed do<br>\r\n                      eiusmod tempor.\r\n                  </span><br><br>\r\n                  <i class=\"fa fa-facebook\"></i>\r\n                  <i class=\"fa fa-twitter\"></i>\r\n                  <i class=\"fa fa-google\"></i>\r\n                  <i class=\"fa fa-youtube\"></i>\r\n                </div>\r\n              </div>\r\n            </div>\r\n          </section>          \r\n        </div>\r\n        <div class=\"col-md-4 col-sm-6 col-xs-12\">\r\n            <section class=\"new-deal\">\r\n            <div class=\"item-slide\">\r\n              <div class=\"box-img\">\r\n                <img src=\"{{image.avatar}}\" alt=\"profile-img\">\r\n              </div>\r\n              <div class=\"row\">\r\n                <div class=\"col-md-10 slide-hover\">\r\n                  <h2>{{name.findName}}</h2>\r\n                  <p>{{name.jobTitle}}</p>\r\n                  <span>Lorem ipsum dolor sit amet,<br>\r\n                    consectetur adipisicing elit, sed do<br>\r\n                      eiusmod tempor.\r\n                  </span><br><br>\r\n                  <i class=\"fa fa-facebook\"></i>\r\n                  <i class=\"fa fa-twitter\"></i>\r\n                  <i class=\"fa fa-google\"></i>\r\n                  <i class=\"fa fa-youtube\"></i>\r\n                </div>\r\n              </div>\r\n            </div>\r\n          </section>          \r\n        </div>\r\n      </div>",
    "div": "<div>Div</div>",
    "forms/checkbox": "\r\n\r\n<div>\r\n    <div class=\"form-check form-check-inline\">\r\n        <input name=\"checkbox[]\" class=\"form-check-input\" type=\"checkbox\" id=\"inlineCheckbox1\" value=\"option1\">\r\n        <label class=\"form-check-label\" for=\"inlineCheckbox1\">Yes</label>\r\n    </div>\r\n    <div class=\"form-check form-check-inline\">\r\n        <input name=\"checkbox[]\" class=\"form-check-input\" type=\"checkbox\" id=\"inlineCheckbox2\" value=\"option2\">\r\n        <label class=\"form-check-label\" for=\"inlineCheckbox2\">No</label>\r\n    </div>\r\n</div>\r\n\r\n",
    "forms/custom_checkbox": "\r\n<div class=\"custom-control custom-checkbox\">\r\n    <input type=\"checkbox\" class=\"custom-control-input\" id=\"customCheck1\">\r\n    <label class=\"custom-control-label\" for=\"customCheck1\">Check this custom checkbox</label>\r\n</div>\r\n",
    "forms/date": "\r\n\r\n<div class=\"form-group\">\r\n    <label for=\"exampleInputdate\">Date</label>\r\n    <input type=\"date\" name=\"date\" class=\"form-control\" id=\"exampleInputdate\" aria-describedby=\"dateHelp\" placeholder=\"e.g. 03/30/2018\">\r\n    <small id=\"urlHelp\" class=\"form-text text-muted\"></small>\r\n</div>\r\n\r\n",
    "forms/dateTime": "\r\n\r\n<div class=\"form-group\">\r\n    <label for=\"exampleInputdatetime\">Date Time</label>\r\n    <input type=\"datetime-local\" name=\"datetime\" class=\"form-control\" id=\"exampleInputdatetime\" aria-describedby=\"dateHelp\" placeholder=\"e.g. 03/30/2018\">\r\n    <small id=\"urlHelp\" class=\"form-text text-muted\"></small>\r\n</div>\r\n\r\n",
    "forms/email": "\r\n\r\n<div class=\"form-group\">\r\n    <label for=\"exampleInputEmail1\">Email address</label>\r\n    <input type=\"email\" name=\"email\" class=\"form-control\" id=\"exampleInputEmail1\" aria-describedby=\"emailHelp\" placeholder=\"Enter email\">\r\n    <small id=\"emailHelp\" class=\"form-text text-muted\">We'll never share your email with anyone else.</small>\r\n</div>\r\n\r\n",
    "forms/file": "\r\n<div class=\"form-group\">\r\n    <label class=\"custom-file-label\" for=\"validatedCustomFile\">File</label>\r\n    <input type=\"file\" class=\"form-control-file\" id=\"validatedCustomFile\" required>\r\n</div>\r\n\r\n",
    "forms/number": "\r\n<div class=\"form-group\">\r\n    <label for=\"exampleNumberText\">Number</label>\r\n    <input type=\"number\" name=\"number\" class=\"form-control\" id=\"exampleNumberText\" aria-describedby=\"textHelp\"\r\n           placeholder=\"e.g. 5\">\r\n</div>\r\n\r\n\r\n\r\n",
    "forms/password": "\r\n\r\n<div class=\"form-group\">\r\n    <label for=\"exampleInputPassword1\">Password</label>\r\n    <input type=\"password\" class=\"form-control\" id=\"exampleInputPassword1\" placeholder=\"Password\">\r\n</div>\r\n\r\n\r\n",
    "forms/radio": "\r\n<div>\r\n    <div class=\"custom-control custom-radio custom-control-inline\">\r\n        <input type=\"radio\" id=\"customRadioInline1\" name=\"customRadioInline1\" class=\"custom-control-input\">\r\n        <label class=\"custom-control-label\" for=\"customRadioInline1\">Toggle this custom radio</label>\r\n    </div>\r\n    <div class=\"custom-control custom-radio custom-control-inline\">\r\n        <input type=\"radio\" id=\"customRadioInline2\" name=\"customRadioInline1\" class=\"custom-control-input\">\r\n        <label class=\"custom-control-label\" for=\"customRadioInline2\">Or toggle this other custom radio</label>\r\n    </div>\r\n</div>\r\n",
    "forms/search": "\r\n\r\n<div class=\"form-group\">\r\n    <label for=\"exampleInputSearch\">Search</label>\r\n    <input type=\"search\" name=\"q\" class=\"form-control\" id=\"exampleInputSearch\" aria-describedby=\"urlHelp\" placeholder=\"e.g. Mark\">\r\n</div>\r\n\r\n",
    "forms/select": "<div class=\"form-group\">\r\n    <label for=\"exampleFormControlSelect1\">select</label>\r\n    <select class=\"form-control\" id=\"exampleFormControlSelect1\">\r\n        <option value=\"\">One</option>\r\n        <option value=\"\">Two</option>\r\n    </select>\r\n</div>\r\n\r\n",
    "forms/submit": "<div class=\"form-group text-right\">\r\n    <input type=\"reset\" class=\"btn btn-secondary\" value=\"Clear\">\r\n    <input type=\"submit\" class=\"btn btn-primary\" value=\"Save\">\r\n</div>\r\n\r\n\r\n",
    "forms/text": "\r\n<div class=\"form-group\">\r\n    <label for=\"exampleInputText\">Text</label>\r\n    <input type=\"text\" name=\"text\" class=\"form-control\" id=\"exampleInputText\" aria-describedby=\"textHelp\"\r\n           placeholder=\"e.g. Tuhin\">\r\n    <small id=\"urlHelp\" class=\"form-text text-muted\">Help text</small>\r\n</div>\r\n\r\n\r\n",
    "forms/time": "\r\n\r\n<div class=\"form-group\">\r\n    <label for=\"exampleInputtime\">Time</label>\r\n    <input type=\"time\" name=\"time\" class=\"form-control\" id=\"exampleInputtime\" aria-describedby=\"dateHelp\" placeholder=\"\">\r\n    <small id=\"urlHelp\" class=\"form-text text-muted\"></small>\r\n</div>\r\n\r\n",
    "forms/url": "\r\n\r\n<div class=\"form-group\">\r\n    <label for=\"exampleInputurl\">Url</label>\r\n    <input type=\"url\" name=\"url\" class=\"form-control\" id=\"exampleInputurl\" aria-describedby=\"urlHelp\" placeholder=\"e.g. http://www.example.com\">\r\n    <small id=\"urlHelp\" class=\"form-text text-muted\">URL must start with http or https</small>\r\n</div>\r\n\r\n",
    "media/default": "\r\n\r\n<div class=\"media\">\r\n    <img class=\"mr-3\" src=\"{{image.avatar}}\" alt=\"Generic placeholder image\">\r\n    <div class=\"media-body\">\r\n        <h5 class=\"mt-0\">{{name.findName}}</h5>\r\n        {{lorem.paragraph}}\r\n    </div>\r\n</div>\r\n\r\n",
    "media/list": "\r\n<ul class=\"list-unstyled\">\r\n    <li class=\"media\">\r\n        <img class=\"mr-3\" src=\"{{image.avatar}}\" alt=\"Generic placeholder image\">\r\n        <div class=\"media-body\">\r\n            <h5 class=\"mt-0 mb-1\">{{name.findName}}</h5>\r\n            {{lorem.paragraph}}\r\n        </div>\r\n    </li>\r\n    <li class=\"media my-4\">\r\n        <img class=\"mr-3\" src=\"{{image.avatar}}\" alt=\"Generic placeholder image\">\r\n        <div class=\"media-body\">\r\n            <h5 class=\"mt-0 mb-1\">{{name.findName}}</h5>\r\n            {{lorem.paragraph}}\r\n        </div>\r\n    </li>\r\n    <li class=\"media\">\r\n        <img class=\"mr-3\" src=\"{{image.avatar}}\" alt=\"Generic placeholder image\">\r\n        <div class=\"media-body\">\r\n            <h5 class=\"mt-0 mb-1\">{{name.findName}}</h5>\r\n            {{lorem.paragraph}}\r\n        </div>\r\n    </li>\r\n</ul>\r\n\r\n\r\n",
    "media/nested": "\r\n\r\n<div class=\"media\">\r\n    <img class=\"mr-3\" src=\"{{image.avatar}}\" alt=\"Generic placeholder image\">\r\n    <div class=\"media-body\">\r\n        <h5 class=\"mt-0\">{{name.findName}}</h5>\r\n        {{lorem.paragraph}}\r\n\r\n        <div class=\"media mt-3\">\r\n            <a class=\"pr-3\" href=\"#\">\r\n                <img src=\"{{image.avatar}}\" alt=\"Generic placeholder image\">\r\n            </a>\r\n            <div class=\"media-body\">\r\n                <h5 class=\"mt-0\">{{name.findName}}</h5>\r\n                {{lorem.paragraph}}\r\n            </div>\r\n        </div>\r\n    </div>\r\n</div>\r\n\r\n",
    "media/right": "\r\n<div class=\"media\">\r\n    <div class=\"media-body\">\r\n        <h5 class=\"mt-0 mb-1\">{{name.findName}}</h5>\r\n        {{lorem.paragraph}}\r\n    </div>\r\n    <img class=\"ml-3\" src=\"{{image.avatar}}\" alt=\"Generic placeholder image\">\r\n</div>\r\n",
    "pages/forget_password": "<form class=\"form-horizontal\" role=\"form\" method=\"POST\" action=\"\">\r\n\r\n    <div class=\"form-group\">\r\n        <label for=\"email\" class=\"\">E-Mail Address</label>\r\n\r\n        <div class=\"\">\r\n            <input id=\"email\" type=\"email\" class=\"form-control\" name=\"email\" value=\"\" placeholder=\"e.g. demo@example.com\" required>\r\n        </div>\r\n    </div>\r\n\r\n    <div class=\"form-group\">\r\n        <div class=\"\">\r\n            <button type=\"submit\" class=\"btn btn-primary\">\r\n                Send Password Reset Link\r\n            </button>\r\n        </div>\r\n    </div>\r\n</form>",
    "pages/login": "<form class=\"form-horizontal\" role=\"form\" method=\"POST\" action=\"\">\r\n\r\n    <div class=\"form-group\">\r\n        <label class=\"form-control-label\" for=\"email\" class=\"\">E-Mail Address</label>\r\n        <div class=\"input-group\">\r\n            <div class=\"input-group-addon\">\r\n                <i class=\"fa fa-envelope\"></i>\r\n            </div>\r\n            <input id=\"email\" type=\"email\" class=\"form-control\"\r\n                   name=\"email\" value=\"\"\r\n                   placeholder=\"e.g. demo@example.com\" required\r\n                   autofocus>\r\n        </div>\r\n\r\n    </div>\r\n\r\n    <div class=\"form-group\">\r\n        <label class=\"form-control-label\" for=\"password\">Password</label>\r\n\r\n        <div class=\"input-group\">\r\n            <div class=\"input-group-addon\">\r\n                <i class=\"fa fa-lock\"></i>\r\n            </div>\r\n            <input id=\"password\" type=\"password\" class=\"form-control\" placeholder=\"Your account password\"\r\n                   name=\"password\" required>\r\n        </div>\r\n    </div>\r\n\r\n    <div class=\"form-group\">\r\n        <div class=\"checkbox\">\r\n            <label>\r\n                <input type=\"checkbox\" name=\"remember\"> Remember Me\r\n            </label>\r\n        </div>\r\n    </div>\r\n\r\n    <div class=\"form-group\">\r\n        <button type=\"submit\" class=\"btn btn-primary\">\r\n            Login\r\n        </button>\r\n    </div>\r\n    <div class=\"form-group\">\r\n        <p>\r\n            <a class=\"btn btn-link\" href=\"#\">\r\n                Forgot Your Password?\r\n            </a>\r\n            <a class=\"btn btn-link\" href=\"#\">\r\n                Register\r\n            </a>\r\n        </p>\r\n\r\n\r\n    </div>\r\n</form>\r\n",
    "pages/register": "<div class=\"row\">\r\n    <div class=\"col-md-6\">\r\n        <div class=\"form-group\">\r\n\r\n            <label for=\"first_name\" class=\"control-label\">First Name</label>\r\n            <input id=\"first_name\" type=\"text\" class=\"form-control\" name=\"first_name\"\r\n                   value=\"\" placeholder=\"e.g. John\" required\r\n                   autofocus>\r\n        </div>\r\n    </div>\r\n    <div class=\"col-md-6\">\r\n        <div class=\"form-group\">\r\n            <label for=\"last_name\" class=\"control-label\">Last Name</label>\r\n\r\n            <input id=\"last_name\" type=\"text\" class=\"form-control\" name=\"last_name\"\r\n                   value=\"\" placeholder=\"e.g. Doe\">\r\n\r\n        </div>\r\n    </div>\r\n\r\n</div>\r\n<div class=\"row\">\r\n    <div class=\"col-sm-6\">\r\n        <div class=\"form-group\">\r\n            <label for=\"email\" class=\"control-label\">E-Mail Address</label>\r\n\r\n            <input id=\"email\" type=\"email\" class=\"form-control\" name=\"email\"\r\n                   value=\"\" placeholder=\"e.g. demo@example.com\" required>\r\n\r\n        </div>\r\n    </div>\r\n    <div class=\"col-sm-6\">\r\n        <div class=\"form-group\">\r\n            <label for=\"phone\" class=\"control-label\">Phone</label>\r\n            <input type=\"text\" class=\"form-control\" id=\"phone\" name=\"phone\" value=\"\"\r\n                   placeholder=\"E.g. +460001000\">\r\n        </div>\r\n    </div>\r\n</div>\r\n\r\n<div class=\"row\">\r\n    <div class=\"col-md-6\">\r\n        <div class=\"form-group\">\r\n            <label for=\"password\" class=\"\">Password</label>\r\n            <input id=\"password\" type=\"password\" placeholder=\"min 6 character\" class=\"form-control\" name=\"password\"\r\n            >\r\n        </div>\r\n    </div>\r\n    <div class=\"col-md-6\">\r\n        <div class=\"form-group\">\r\n            <label for=\"password-confirm\" class=\"\">Confirm Password</label>\r\n            <input id=\"password-confirm\" type=\"password\" placeholder=\"Write again\" class=\"form-control\"\r\n                   name=\"password_confirmation\">\r\n        </div>\r\n    </div>\r\n</div>\r\n<div class=\"form-row\">\r\n    <div class=\"form-group col\">\r\n        <label for=\"address\">Address</label>\r\n        <div class=\"input-group\">\r\n            <div class=\"input-group-addon\">\r\n                <a title=\"Get your current address  by click here.\" href=\"#\"><i class=\"fa fa-map-marker\"></i></a>\r\n            </div>\r\n            <input type=\"text\" class=\"form-control address\" name=\"address\" value=\"\"\r\n                   placeholder=\"Your current address\" id=\"address\">\r\n\r\n        </div>\r\n\r\n    </div>\r\n    <div class=\"form-group col\">\r\n        <label for=\"phone\">Phone Number</label>\r\n        <div class=\"input-group\">\r\n            <div class=\"input-group-addon\">\r\n                <i class=\"fa fa-mobile\"></i>\r\n            </div>\r\n            <input type=\"text\" class=\"form-control address\" name=\"phone\" value=\"\"\r\n                   placeholder=\"e.g. +88019...\" id=\"phone\">\r\n\r\n        </div>\r\n\r\n    </div>\r\n</div>\r\n\r\n\r\n",
    "pages/reset password": "<form class=\"form-horizontal\" role=\"form\" method=\"POST\" action=\"\">\r\n\r\n    <div class=\"form-group\">\r\n        <label for=\"password\" class=\"\">Current Password</label>\r\n\r\n        <div class=\"\">\r\n            <input id=\"current_password\" type=\"password\" class=\"form-control\" name=\"current_password\" required>\r\n        </div>\r\n    </div>\r\n\r\n    <div class=\"form-group\">\r\n        <label for=\"password\" class=\"\">Password</label>\r\n        <div class=\"\">\r\n            <input id=\"password\" type=\"password\" class=\"form-control\" name=\"password\" required>\r\n        </div>\r\n    </div>\r\n\r\n    <div class=\"form-group\">\r\n        <label for=\"password-confirm\" class=\"\">Confirm Password</label>\r\n        <div class=\"\">\r\n            <input id=\"password-confirm\" type=\"password\" class=\"form-control\" name=\"password_confirmation\" required>\r\n\r\n        </div>\r\n    </div>\r\n\r\n    <div class=\"form-group text-right\">\r\n\r\n        <button type=\"submit\" class=\"btn btn-primary\">\r\n            Reset Password\r\n        </button>\r\n    </div>\r\n</form>",
    "pages/search_box": "<form class=\"form-inline\">\r\n    <input class=\"form-control mr-sm-2\" type=\"search\" placeholder=\"Search\" aria-label=\"Search\">\r\n    <button class=\"btn btn-outline-success my-2 my-sm-0\" type=\"submit\">Search</button>\r\n</form>",
    "pickers/date": "\r\n<div class=\"form-group\">\r\n    <label for=\"datetimepicker-date\">Date</label>\r\n    <div class=\"input-group\">\r\n        <input type=\"text\" class=\"form-control datetimepicker-date\" id=\"datetimepicker-date\" placeholder=\"e.g. 06/28/2017\"\r\n               data-date=\"12-02-2012\" data-date-format=\"dd/mm/yyyy\"\r\n        >\r\n        <div class=\"input-group-addon\">\r\n            <label for=\"datetimepicker-date\" class=\"fa fa-calendar\"></label>\r\n        </div>\r\n    </div>\r\n</div>\r\n",
    "pickers/date_time": "\r\n<div class=\"form-group\">\r\n    <label for=\"datetimepicker-date-time\">Date Time</label>\r\n    <div class=\"input-group\">\r\n        <input type=\"text\" class=\"form-control datetimepicker-date-time\" id=\"datetimepicker-date-time\"\r\n               placeholder=\"e.g. 06/28/2017 12:00 am\" data-date-format=\"dd/mm/yyyy HH:ii p\"\r\n        >\r\n        <div class=\"input-group-addon\">\r\n            <label for=\"datetimepicker-date-time\" class=\"fa fa-calendar\"></label>\r\n        </div>\r\n    </div>\r\n</div>\r\n",
    "pickers/future_date": "\r\n<div class=\"form-group\">\r\n    <label for=\"datetimepicker-future-date\">Future Date</label>\r\n    <div class=\"input-group\">\r\n        <input type=\"text\" class=\"form-control datetimepicker-future-date\" id=\"datetimepicker-future-date\"\r\n               placeholder=\"e.g. 06/28/2017\" data-date-format=\"dd/mm/yyyy\"\r\n        >\r\n        <div class=\"input-group-addon\">\r\n            <label for=\"datetimepicker-future-date\" class=\"fa fa-calendar\"></label>\r\n        </div>\r\n    </div>\r\n</div>\r\n",
    "pickers/future_date_time": "\r\n<div class=\"form-group\">\r\n    <label for=\"datetimepicker-future-date-time\">Future Date Time</label>\r\n    <div class=\"input-group\">\r\n        <input type=\"text\" class=\"form-control datetimepicker-future-date-time\" id=\"datetimepicker-future-date-time\"\r\n               placeholder=\"e.g. 06/28/2017\" data-date-format=\"dd/mm/yyyy HH:ii p\"\r\n        >\r\n        <div class=\"input-group-addon\">\r\n            <label for=\"datetimepicker-future-date-time\" class=\"fa fa-calendar\"></label>\r\n        </div>\r\n    </div>\r\n</div>\r\n",
    "pickers/past_date": "\r\n<div class=\"form-group\">\r\n    <label for=\"datetimepicker-past-date\">Past Date</label>\r\n    <div class=\"input-group\">\r\n        <input type=\"text\" class=\"form-control datetimepicker-past-date\" id=\"datetimepicker-past-date\"\r\n               placeholder=\"e.g. 06/28/2017\" data-date-format=\"dd/mm/yyyy\"\r\n        >\r\n        <div class=\"input-group-addon\">\r\n            <label for=\"datetimepicker-past-date\" class=\"fa fa-calendar\"></label>\r\n        </div>\r\n    </div>\r\n</div>\r\n\r\n",
    "pickers/past_date_time": "\r\n<div class=\"form-group\">\r\n    <label for=\"datetimepicker-past-date-time\">Past Date Time</label>\r\n    <div class=\"input-group\">\r\n        <input type=\"text\" class=\"form-control datetimepicker-past-date-time\" id=\"datetimepicker-past-date-time\"\r\n               placeholder=\"e.g. 06/28/2017 12:00 am\" data-date-format=\"dd/mm/yyyy HH:ii p\"\r\n        >\r\n        <div class=\"input-group-addon\">\r\n            <label for=\"datetimepicker-past-date-time\" class=\"fa fa-calendar\"></label>\r\n        </div>\r\n    </div>\r\n</div>\r\n\r\n",
    "pickers/time": "\r\n<div class=\"form-group\">\r\n    <label for=\"datetimepicker-time\">Time</label>\r\n    <div class=\"input-group\">\r\n        <input type=\"text\" class=\"form-control datetimepicker-time\" id=\"datetimepicker-time\"\r\n               placeholder=\"e.g. 12:00 AM\" data-date-format=\"HH:ii p\"\r\n        >\r\n        <div class=\"input-group-addon\">\r\n            <label for=\"datetimepicker-time\" class=\"fa fa-calendar\"></label>\r\n        </div>\r\n    </div>\r\n</div>\r\n"
};
var bootstrap4Toolbars = ['bootstrap4.cards', 'bootstrap4.components', 'bootstrap4.Div', 'bootstrap4.forms', 'bootstrap4.media', 'bootstrap4.pages', 'bootstrap4.pickers'];
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
        'bootstrap4.cards': function (context) {

            return addDropdown(context, 'bootstrap4.cards', 'cards', Object.values({
                "3_users": "3_users",
                "4_products": "4_products",
                "Header_footer": "header_footer",
                "Image_title": "image_title",
                "Title_link": "title_link"
            }), bootstrap4TemplateContents);

        },
        'bootstrap4.components': function (context) {

            return addDropdown(context, 'bootstrap4.components', 'components', Object.values({
                "Blockquote": "blockquote",
                "Carousel": "carousel",
                "Chatbox": "chatbox",
                "Collapse": "collapse",
                "Dropdown": "dropdown",
                "Jumbotron": "jumbotron",
                "List_group": "list_group",
                "Modal": "modal",
                "Navbar": "navbar",
                "Nav_tabs": "nav_tabs",
                "Pagination": "pagination",
                "Post": "post",
                "Profile_banner": "profile_banner",
                "Rating": "rating",
                "Round_users_box": "round_users_box"
            }), bootstrap4TemplateContents);

        },
        'bootstrap4.Div': function (context) {

            var content = bootstrap4TemplateContents['div']

            return addMenu(context, 'bootstrap4.Div', 'Div', content);

        },
        'bootstrap4.forms': function (context) {

            return addDropdown(context, 'bootstrap4.forms', 'forms', Object.values({
                "Checkbox": "checkbox",
                "Custom_checkbox": "custom_checkbox",
                "Date": "date",
                "DateTime": "dateTime",
                "Email": "email",
                "File": "file",
                "Number": "number",
                "Password": "password",
                "Radio": "radio",
                "Search": "search",
                "Select": "select",
                "Submit": "submit",
                "Text": "text",
                "Time": "time",
                "Url": "url"
            }), bootstrap4TemplateContents);

        },
        'bootstrap4.media': function (context) {

            return addDropdown(context, 'bootstrap4.media', 'media', Object.values({
                "Default": "default",
                "List": "list",
                "Nested": "nested",
                "Right": "right"
            }), bootstrap4TemplateContents);

        },
        'bootstrap4.pages': function (context) {

            return addDropdown(context, 'bootstrap4.pages', 'pages', Object.values({
                "Forget_password": "forget_password",
                "Login": "login",
                "Register": "register",
                "Reset password": "reset password",
                "Search_box": "search_box"
            }), bootstrap4TemplateContents);

        },
        'bootstrap4.pickers': function (context) {

            return addDropdown(context, 'bootstrap4.pickers', 'pickers', Object.values({
                "Date": "date",
                "Date_time": "date_time",
                "Future_date": "future_date",
                "Future_date_time": "future_date_time",
                "Past_date": "past_date",
                "Past_date_time": "past_date_time",
                "Time": "time"
            }), bootstrap4TemplateContents);

        },

    });
}));

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

var bootstrapTemplateContents = {
    "cards\/3_users": "<div class=\"card-deck\">\r\n    <div class=\"card\">\r\n        <img class=\"card-img-top\" src=\"{{image.avatar}}\" alt=\"Card image cap\">\r\n        <div class=\"card-body\">\r\n            <h5 class=\"card-title\">{{name.findName}}<\/h5>\r\n            <p class=\"card-text\">{{name.jobTitle}}<\/p>\r\n            <p class=\"card-text\"><small class=\"text-muted\">Last updated 3 mins ago<\/small><\/p>\r\n        <\/div>\r\n    <\/div>\r\n    <div class=\"card\">\r\n        <img class=\"card-img-top\" src=\"{{image.avatar}}\" alt=\"Card image cap\">\r\n        <div class=\"card-body\">\r\n            <h5 class=\"card-title\">{{name.findName}}<\/h5>\r\n            <p class=\"card-text\">{{name.jobTitle}}<\/p>\r\n            <p class=\"card-text\"><small class=\"text-muted\">Last updated 3 mins ago<\/small><\/p>\r\n        <\/div>\r\n    <\/div>\r\n    <div class=\"card\">\r\n        <img class=\"card-img-top\" src=\"{{image.avatar}}\" alt=\"Card image cap\">\r\n        <div class=\"card-body\">\r\n            <h5 class=\"card-title\">{{name.findName}}<\/h5>\r\n            <p class=\"card-text\">{{name.jobTitle}}<\/p>\r\n            <p class=\"card-text\"><small class=\"text-muted\">Last updated 3 mins ago<\/small><\/p>\r\n        <\/div>\r\n    <\/div>\r\n<\/div>\r\n",
    "cards\/4_products": "<div class=\"card-deck\">\r\n    <div class=\"card\">\r\n        <img class=\"card-img-top\" src=\"{{image.abstract}}\" alt=\"Card image cap\">\r\n        <div class=\"card-body\">\r\n            <h5 class=\"card-title\">\r\n                {{commerce.productName}}\r\n                <label class=\"badge badge-primary badge-pill\">${{commerce.price}}<\/label>\r\n            <\/h5>\r\n            <p class=\"card-text\">This is a longer card with supporting text below as a natural lead-in to additional\r\n                content. This content is a little bit longer.<\/p>\r\n            <p class=\"card-text\">\r\n                <label class=\"badge badge-secondary\">{{commerce.productAdjective}}<\/label>\r\n                <small class=\"text-muted\">Last updated 3 mins ago<\/small>\r\n            <\/p>\r\n        <\/div>\r\n    <\/div>\r\n    <div class=\"card\">\r\n        <img class=\"card-img-top\" src=\"{{image.abstract}}\" alt=\"Card image cap\">\r\n        <div class=\"card-body\">\r\n            <h5 class=\"card-title\">\r\n                {{commerce.productName}}\r\n                <label class=\"badge badge-primary badge-pill\">${{commerce.price}}<\/label>\r\n            <\/h5>\r\n            <p class=\"card-text\">This card has supporting text below as a natural lead-in to additional content.<\/p>\r\n            <p class=\"card-text\">\r\n                <label class=\"badge badge-secondary\">{{commerce.productAdjective}}<\/label>\r\n                <small class=\"text-muted\">Last updated 3 mins ago<\/small>\r\n            <\/p>\r\n        <\/div>\r\n    <\/div>\r\n    <div class=\"card\">\r\n        <img class=\"card-img-top\" src=\"{{image.abstract}}\" alt=\"Card image cap\">\r\n        <div class=\"card-body\">\r\n            <h5 class=\"card-title\">\r\n                {{commerce.productName}}\r\n                <label class=\"badge badge-primary badge-pill\">${{commerce.price}}<\/label>\r\n            <\/h5>\r\n            <p class=\"card-text\">This is a wider card with supporting text below as a natural lead-in to additional\r\n                content. This card has even longer content than the first to show that equal height action.<\/p>\r\n            <p class=\"card-text\">\r\n                <label class=\"badge badge-secondary\">{{commerce.productAdjective}}<\/label>\r\n                <small class=\"text-muted\">Last updated 3 mins ago<\/small>\r\n            <\/p>\r\n        <\/div>\r\n    <\/div>\r\n    <div class=\"card\">\r\n        <img class=\"card-img-top\" src=\"{{image.abstract}}\" alt=\"Card image cap\">\r\n        <div class=\"card-body\">\r\n            <h5 class=\"card-title\">\r\n                {{commerce.productName}}\r\n                <label class=\"badge badge-primary badge-pill\">${{commerce.price}}<\/label>\r\n            <\/h5>\r\n            <p class=\"card-text\">This is a wider card with supporting text below as a natural lead-in to additional\r\n                content. This card has even longer content than the first to show that equal height action.<\/p>\r\n            <p class=\"card-text\">\r\n                <label class=\"badge badge-secondary\">{{commerce.productAdjective}}<\/label>\r\n                <small class=\"text-muted\">Last updated 3 mins ago<\/small>\r\n            <\/p>\r\n        <\/div>\r\n    <\/div>\r\n<\/div>\r\n",
    "cards\/header_footer": "\r\n\r\n<div class=\"card mb-3\">\r\n    <div class=\"card-header\">{{lorem.sentence}}<\/div>\r\n    <div class=\"card-body\">\r\n        <h5 class=\"card-title\">{{lorem.sentence}}<\/h5>\r\n        <p class=\"card-text\">{{lorem.sentences}}<\/p>\r\n    <\/div>\r\n    <div class=\"card-footer\">\r\n        <p class=\"card-text\"><small class=\"text-muted\">Last updated 3 mins ago<\/small><\/p>\r\n    <\/div>\r\n<\/div>\r\n\r\n",
    "cards\/image_title": "\r\n\r\n<div class=\"card\">\r\n    <img class=\"card-img-top\" src=\"{{image.imageUrl}}\" alt=\"Card image cap\">\r\n\r\n    <div class=\"card-body\">\r\n        <h5 class=\"card-title\">{{lorem.sentence}}<\/h5>\r\n\r\n        <p class=\"card-text\">{{lorem.paragraph}}<\/p>\r\n        <p class=\"card-text\"><small class=\"text-muted\">Last updated 3 mins ago<\/small><\/p>\r\n\r\n    <\/div>\r\n<\/div>\r\n\r\n",
    "cards\/title_link": "\r\n<div class=\"card\">\r\n    <div class=\"card-body\">\r\n        <h5 class=\"card-title\">{{lorem.sentence}}<\/h5>\r\n        <p class=\"card-text\">{{lorem.sentences}}<\/p>\r\n        <a href=\"#\" class=\"btn btn-primary\">Button<\/a>\r\n    <\/div>\r\n<\/div>\r\n\r\n",
    "components\/blockquote": "\r\n<blockquote class=\"blockquote text-right\">\r\n    <p class=\"mb-0\">{{lorem.sentence}}<\/p>\r\n    <footer class=\"blockquote-footer\">{{name.findName}} in <cite title=\"Source Title\">{{name.jobArea}}<\/cite><\/footer>\r\n<\/blockquote>\r\n",
    "components\/carousel": "\r\n\r\n<div id=\"carouselExampleIndicators\" class=\"carousel slide\" data-ride=\"carousel\">\r\n    <ol class=\"carousel-indicators\">\r\n        <li data-target=\"#carouselExampleIndicators\" data-slide-to=\"0\" class=\"active\"><\/li>\r\n        <li data-target=\"#carouselExampleIndicators\" data-slide-to=\"1\"><\/li>\r\n        <li data-target=\"#carouselExampleIndicators\" data-slide-to=\"2\"><\/li>\r\n    <\/ol>\r\n    <div class=\"carousel-inner\">\r\n        <div class=\"carousel-item active\">\r\n            <img class=\"d-block w-100\" src=\"\/images\/carousel\/desert.jpg\" alt=\"First slide\">\r\n            <div class=\"carousel-caption d-none d-md-block\">\r\n                <h5>{{lorem.words}}<\/h5>\r\n                <p>{{lorem.sentence}}<\/p>\r\n            <\/div>\r\n        <\/div>\r\n        <div class=\"carousel-item\">\r\n            <img class=\"d-block w-100\" src=\"\/images\/carousel\/penguins.jpg\" alt=\"Second slide\">\r\n            <div class=\"carousel-caption d-none d-md-block\">\r\n                <h5>{{lorem.words}}<\/h5>\r\n                <p>{{lorem.sentence}}<\/p>\r\n            <\/div>\r\n        <\/div>\r\n        <div class=\"carousel-item\">\r\n            <img class=\"d-block w-100\" src=\"\/images\/carousel\/tulips.jpg\" alt=\"Third slide\">\r\n            <div class=\"carousel-caption d-none d-md-block\">\r\n                <h5>{{lorem.words}}<\/h5>\r\n                <p>{{lorem.sentence}}<\/p>\r\n            <\/div>\r\n        <\/div>\r\n    <\/div>\r\n    <a class=\"carousel-control-prev\" href=\"#carouselExampleIndicators\" role=\"button\" data-slide=\"prev\">\r\n        <span class=\"carousel-control-prev-icon\" aria-hidden=\"true\"><\/span>\r\n        <span class=\"sr-only\">Previous<\/span>\r\n    <\/a>\r\n    <a class=\"carousel-control-next\" href=\"#carouselExampleIndicators\" role=\"button\" data-slide=\"next\">\r\n        <span class=\"carousel-control-next-icon\" aria-hidden=\"true\"><\/span>\r\n        <span class=\"sr-only\">Next<\/span>\r\n    <\/a>\r\n<\/div>\r\n\r\n\r\n\r\n",
    "components\/collapse": "\r\n\r\n<div id=\"accordion\">\r\n    <div class=\"card\">\r\n        <div class=\"card-header\" id=\"headingOne\">\r\n            <h5 class=\"mb-0\">\r\n                <button class=\"btn btn-link\" data-toggle=\"collapse\" data-target=\"#collapseOne\" aria-expanded=\"true\"\r\n                        aria-controls=\"collapseOne\">\r\n                    {{lorem.sentence}}\r\n                <\/button>\r\n            <\/h5>\r\n        <\/div>\r\n\r\n        <div id=\"collapseOne\" class=\"collapse show\" aria-labelledby=\"headingOne\" data-parent=\"#accordion\">\r\n            <div class=\"card-body\">\r\n                {{lorem.paragraph}}\r\n            <\/div>\r\n        <\/div>\r\n    <\/div>\r\n    <div class=\"card\">\r\n        <div class=\"card-header\" id=\"headingTwo\">\r\n            <h5 class=\"mb-0\">\r\n                <button class=\"btn btn-link collapsed\" data-toggle=\"collapse\" data-target=\"#collapseTwo\"\r\n                        aria-expanded=\"false\" aria-controls=\"collapseTwo\">\r\n                    {{lorem.sentence}}\r\n                <\/button>\r\n            <\/h5>\r\n        <\/div>\r\n        <div id=\"collapseTwo\" class=\"collapse\" aria-labelledby=\"headingTwo\" data-parent=\"#accordion\">\r\n            <div class=\"card-body\">\r\n                {{lorem.paragraph}}\r\n            <\/div>\r\n        <\/div>\r\n    <\/div>\r\n    <div class=\"card\">\r\n        <div class=\"card-header\" id=\"headingThree\">\r\n            <h5 class=\"mb-0\">\r\n                <button class=\"btn btn-link collapsed\" data-toggle=\"collapse\" data-target=\"#collapseThree\"\r\n                        aria-expanded=\"false\" aria-controls=\"collapseThree\">\r\n                    {{lorem.sentence}}\r\n                <\/button>\r\n            <\/h5>\r\n        <\/div>\r\n        <div id=\"collapseThree\" class=\"collapse\" aria-labelledby=\"headingThree\" data-parent=\"#accordion\">\r\n            <div class=\"card-body\">\r\n                {{lorem.paragraph}}\r\n            <\/div>\r\n        <\/div>\r\n    <\/div>\r\n<\/div>\r\n\r\n\r\n",
    "components\/dropdown": "\r\n\r\n<div class=\"dropdown\">\r\n    <button class=\"btn btn-secondary dropdown-toggle\" type=\"button\" id=\"dropdownMenu2\" data-toggle=\"dropdown\"\r\n            aria-haspopup=\"true\" aria-expanded=\"false\">\r\n        {{lorem.word}}\r\n    <\/button>\r\n    <div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenu2\">\r\n        <button class=\"dropdown-item\" type=\"button\">{{lorem.word}}<\/button>\r\n        <button class=\"dropdown-item\" type=\"button\">{{lorem.word}}<\/button>\r\n        <div class=\"dropdown-divider\"><\/div>\r\n        <button class=\"dropdown-item\" type=\"button\">{{lorem.word}}<\/button>\r\n    <\/div>\r\n<\/div>\r\n\r\n",
    "components\/jumbotron": "\r\n\r\n<div class=\"jumbotron\">\r\n    <h1 class=\"display-4\">Hello, world!<\/h1>\r\n    <p class=\"lead\">This is a simple hero unit, a simple jumbotron-style component for calling extra attention to\r\n        featured content or information.<\/p>\r\n    <hr class=\"my-4\">\r\n    <p>It uses utility classes for typography and spacing to space content out within the larger container.<\/p>\r\n    <p class=\"lead\">\r\n        <a class=\"btn btn-primary btn-lg\" href=\"#\" role=\"button\">Learn more<\/a>\r\n    <\/p>\r\n<\/div>\r\n\r\n",
    "components\/list_group": "\r\n\r\n<ul class=\"list-group\">\r\n    <li class=\"list-group-item\">{{lorem.sentence}}<\/li>\r\n    <li class=\"list-group-item\">{{lorem.sentence}}<\/li>\r\n    <li class=\"list-group-item\">{{lorem.sentence}}<\/li>\r\n    <li class=\"list-group-item\">{{lorem.sentence}}<\/li>\r\n    <li class=\"list-group-item\">{{lorem.sentence}}<\/li>\r\n<\/ul>\r\n\r\n",
    "components\/modal": "\r\n\r\n<!-- Button trigger modal -->\r\n<button type=\"button\" class=\"btn btn-primary\" data-toggle=\"modal\" data-target=\"#exampleModalCenter\">\r\n    Launch modal\r\n<\/button>\r\n\r\n<!-- Modal -->\r\n<div class=\"modal fade\" id=\"exampleModalCenter\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"exampleModalCenterTitle\"\r\n     aria-hidden=\"true\">\r\n    <div class=\"modal-dialog modal-dialog-centered\" role=\"document\">\r\n        <div class=\"modal-content\">\r\n            <div class=\"modal-header\">\r\n                <h5 class=\"modal-title\" id=\"exampleModalLongTitle\">Modal title<\/h5>\r\n                <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">\r\n                    <span aria-hidden=\"true\">&times;<\/span>\r\n                <\/button>\r\n            <\/div>\r\n            <div class=\"modal-body\">\r\n                Your text\r\n            <\/div>\r\n            <div class=\"modal-footer\">\r\n                <button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">Close<\/button>\r\n                <button type=\"button\" class=\"btn btn-primary\">Save<\/button>\r\n            <\/div>\r\n        <\/div>\r\n    <\/div>\r\n<\/div>\r\n\r\n",
    "components\/navbar": "\r\n\r\n<nav class=\"navbar navbar-expand-lg navbar-light bg-light\">\r\n    <a class=\"navbar-brand\" href=\"#\">Navbar<\/a>\r\n    <button class=\"navbar-toggler\" type=\"button\" data-toggle=\"collapse\" data-target=\"#navbarNavDropdown\"\r\n            aria-controls=\"navbarNavDropdown\" aria-expanded=\"false\" aria-label=\"Toggle navigation\">\r\n        <span class=\"navbar-toggler-icon\"><\/span>\r\n    <\/button>\r\n    <div class=\"collapse navbar-collapse\" id=\"navbarNavDropdown\">\r\n        <ul class=\"navbar-nav\">\r\n            <li class=\"nav-item active\">\r\n                <a class=\"nav-link\" href=\"#\">Home <span class=\"sr-only\">(current)<\/span><\/a>\r\n            <\/li>\r\n            <li class=\"nav-item\">\r\n                <a class=\"nav-link\" href=\"#\">Features<\/a>\r\n            <\/li>\r\n            <li class=\"nav-item\">\r\n                <a class=\"nav-link\" href=\"#\">Pricing<\/a>\r\n            <\/li>\r\n            <li class=\"nav-item dropdown\">\r\n                <a class=\"nav-link dropdown-toggle\" href=\"#\" id=\"navbarDropdownMenuLink\" data-toggle=\"dropdown\"\r\n                   aria-haspopup=\"true\" aria-expanded=\"false\">\r\n                    Dropdown link\r\n                <\/a>\r\n                <div class=\"dropdown-menu\" aria-labelledby=\"navbarDropdownMenuLink\">\r\n                    <a class=\"dropdown-item\" href=\"#\">Action<\/a>\r\n                    <a class=\"dropdown-item\" href=\"#\">Another action<\/a>\r\n                    <a class=\"dropdown-item\" href=\"#\">Something else here<\/a>\r\n                <\/div>\r\n            <\/li>\r\n        <\/ul>\r\n    <\/div>\r\n<\/nav>\r\n\r\n",
    "components\/nav_tabs": "\r\n\r\n<div>\r\n    <nav>\r\n        <div class=\"nav nav-tabs\" id=\"nav-tab\" role=\"tablist\">\r\n            <a class=\"nav-item nav-link active\" id=\"nav-home-tab\" data-toggle=\"tab\" href=\"#nav-home\" role=\"tab\"\r\n               aria-controls=\"nav-home\" aria-selected=\"true\">Home<\/a>\r\n            <a class=\"nav-item nav-link\" id=\"nav-profile-tab\" data-toggle=\"tab\" href=\"#nav-profile\" role=\"tab\"\r\n               aria-controls=\"nav-profile\" aria-selected=\"false\">Profile<\/a>\r\n            <a class=\"nav-item nav-link\" id=\"nav-contact-tab\" data-toggle=\"tab\" href=\"#nav-contact\" role=\"tab\"\r\n               aria-controls=\"nav-contact\" aria-selected=\"false\">Contact<\/a>\r\n        <\/div>\r\n    <\/nav>\r\n    <div class=\"tab-content\" id=\"nav-tabContent\">\r\n        <div class=\"tab-pane fade show active\" id=\"nav-home\" role=\"tabpanel\" aria-labelledby=\"nav-home-tab\">...<\/div>\r\n        <div class=\"tab-pane fade\" id=\"nav-profile\" role=\"tabpanel\" aria-labelledby=\"nav-profile-tab\">...<\/div>\r\n        <div class=\"tab-pane fade\" id=\"nav-contact\" role=\"tabpanel\" aria-labelledby=\"nav-contact-tab\">...<\/div>\r\n    <\/div>\r\n<\/div>\r\n\r\n",
    "components\/pagination": "\r\n\r\n<nav aria-label=\"Page navigation example\">\r\n    <ul class=\"pagination\">\r\n        <li class=\"page-item\">\r\n            <a class=\"page-link\" href=\"#\" aria-label=\"Previous\">\r\n                <span aria-hidden=\"true\">&laquo;<\/span>\r\n                <span class=\"sr-only\">Previous<\/span>\r\n            <\/a>\r\n        <\/li>\r\n        <li class=\"page-item\"><a class=\"page-link\" href=\"#\">1<\/a><\/li>\r\n        <li class=\"page-item\"><a class=\"page-link\" href=\"#\">2<\/a><\/li>\r\n        <li class=\"page-item\"><a class=\"page-link\" href=\"#\">3<\/a><\/li>\r\n        <li class=\"page-item\">\r\n            <a class=\"page-link\" href=\"#\" aria-label=\"Next\">\r\n                <span aria-hidden=\"true\">&raquo;<\/span>\r\n                <span class=\"sr-only\">Next<\/span>\r\n            <\/a>\r\n        <\/li>\r\n    <\/ul>\r\n<\/nav>\r\n\r\n",
    "div": "<div>Div<\/div>",
    "forms\/checkbox": "\r\n\r\n<div>\r\n    <div class=\"form-check form-check-inline\">\r\n        <input name=\"checkbox[]\" class=\"form-check-input\" type=\"checkbox\" id=\"inlineCheckbox1\" value=\"option1\">\r\n        <label class=\"form-check-label\" for=\"inlineCheckbox1\">Yes<\/label>\r\n    <\/div>\r\n    <div class=\"form-check form-check-inline\">\r\n        <input name=\"checkbox[]\" class=\"form-check-input\" type=\"checkbox\" id=\"inlineCheckbox2\" value=\"option2\">\r\n        <label class=\"form-check-label\" for=\"inlineCheckbox2\">No<\/label>\r\n    <\/div>\r\n<\/div>\r\n\r\n",
    "forms\/custom_checkbox": "\r\n<div class=\"custom-control custom-checkbox\">\r\n    <input type=\"checkbox\" class=\"custom-control-input\" id=\"customCheck1\">\r\n    <label class=\"custom-control-label\" for=\"customCheck1\">Check this custom checkbox<\/label>\r\n<\/div>\r\n",
    "forms\/date": "\r\n\r\n<div class=\"form-group\">\r\n    <label for=\"exampleInputdate\">Date<\/label>\r\n    <input type=\"date\" name=\"date\" class=\"form-control\" id=\"exampleInputdate\" aria-describedby=\"dateHelp\" placeholder=\"e.g. 03\/30\/2018\">\r\n    <small id=\"urlHelp\" class=\"form-text text-muted\"><\/small>\r\n<\/div>\r\n\r\n",
    "forms\/dateTime": "\r\n\r\n<div class=\"form-group\">\r\n    <label for=\"exampleInputdatetime\">Date Time<\/label>\r\n    <input type=\"datetime-local\" name=\"datetime\" class=\"form-control\" id=\"exampleInputdatetime\" aria-describedby=\"dateHelp\" placeholder=\"e.g. 03\/30\/2018\">\r\n    <small id=\"urlHelp\" class=\"form-text text-muted\"><\/small>\r\n<\/div>\r\n\r\n",
    "forms\/email": "\r\n\r\n<div class=\"form-group\">\r\n    <label for=\"exampleInputEmail1\">Email address<\/label>\r\n    <input type=\"email\" name=\"email\" class=\"form-control\" id=\"exampleInputEmail1\" aria-describedby=\"emailHelp\" placeholder=\"Enter email\">\r\n    <small id=\"emailHelp\" class=\"form-text text-muted\">We'll never share your email with anyone else.<\/small>\r\n<\/div>\r\n\r\n",
    "forms\/file": "\r\n<div class=\"form-group\">\r\n    <label class=\"custom-file-label\" for=\"validatedCustomFile\">File<\/label>\r\n    <input type=\"file\" class=\"form-control-file\" id=\"validatedCustomFile\" required>\r\n<\/div>\r\n\r\n",
    "forms\/number": "\r\n<div class=\"form-group\">\r\n    <label for=\"exampleNumberText\">Number<\/label>\r\n    <input type=\"number\" name=\"number\" class=\"form-control\" id=\"exampleNumberText\" aria-describedby=\"textHelp\"\r\n           placeholder=\"e.g. 5\">\r\n<\/div>\r\n\r\n\r\n\r\n",
    "forms\/password": "\r\n\r\n<div class=\"form-group\">\r\n    <label for=\"exampleInputPassword1\">Password<\/label>\r\n    <input type=\"password\" class=\"form-control\" id=\"exampleInputPassword1\" placeholder=\"Password\">\r\n<\/div>\r\n\r\n\r\n",
    "forms\/radio": "\r\n<div>\r\n    <div class=\"custom-control custom-radio custom-control-inline\">\r\n        <input type=\"radio\" id=\"customRadioInline1\" name=\"customRadioInline1\" class=\"custom-control-input\">\r\n        <label class=\"custom-control-label\" for=\"customRadioInline1\">Toggle this custom radio<\/label>\r\n    <\/div>\r\n    <div class=\"custom-control custom-radio custom-control-inline\">\r\n        <input type=\"radio\" id=\"customRadioInline2\" name=\"customRadioInline1\" class=\"custom-control-input\">\r\n        <label class=\"custom-control-label\" for=\"customRadioInline2\">Or toggle this other custom radio<\/label>\r\n    <\/div>\r\n<\/div>\r\n",
    "forms\/search": "\r\n\r\n<div class=\"form-group\">\r\n    <label for=\"exampleInputSearch\">Search<\/label>\r\n    <input type=\"search\" name=\"q\" class=\"form-control\" id=\"exampleInputSearch\" aria-describedby=\"urlHelp\" placeholder=\"e.g. Mark\">\r\n<\/div>\r\n\r\n",
    "forms\/select": "<div class=\"form-group\">\r\n    <label for=\"exampleFormControlSelect1\">select<\/label>\r\n    <select class=\"form-control\" id=\"exampleFormControlSelect1\">\r\n        <option value=\"\">One<\/option>\r\n        <option value=\"\">Two<\/option>\r\n    <\/select>\r\n<\/div>\r\n\r\n",
    "forms\/submit": "<div class=\"form-group text-right\">\r\n    <input type=\"reset\" class=\"btn btn-secondary\" value=\"Clear\">\r\n    <input type=\"submit\" class=\"btn btn-primary\" value=\"Save\">\r\n<\/div>\r\n\r\n\r\n",
    "forms\/text": "\r\n<div class=\"form-group\">\r\n    <label for=\"exampleInputText\">Text<\/label>\r\n    <input type=\"text\" name=\"text\" class=\"form-control\" id=\"exampleInputText\" aria-describedby=\"textHelp\"\r\n           placeholder=\"e.g. Tuhin\">\r\n    <small id=\"urlHelp\" class=\"form-text text-muted\">Help text<\/small>\r\n<\/div>\r\n\r\n\r\n",
    "forms\/time": "\r\n\r\n<div class=\"form-group\">\r\n    <label for=\"exampleInputtime\">Time<\/label>\r\n    <input type=\"time\" name=\"time\" class=\"form-control\" id=\"exampleInputtime\" aria-describedby=\"dateHelp\" placeholder=\"\">\r\n    <small id=\"urlHelp\" class=\"form-text text-muted\"><\/small>\r\n<\/div>\r\n\r\n",
    "forms\/url": "\r\n\r\n<div class=\"form-group\">\r\n    <label for=\"exampleInputurl\">Url<\/label>\r\n    <input type=\"url\" name=\"url\" class=\"form-control\" id=\"exampleInputurl\" aria-describedby=\"urlHelp\" placeholder=\"e.g. http:\/\/www.example.com\">\r\n    <small id=\"urlHelp\" class=\"form-text text-muted\">URL must start with http or https<\/small>\r\n<\/div>\r\n\r\n",
    "media\/default": "\r\n\r\n<div class=\"media\">\r\n    <img class=\"mr-3\" src=\"{{image.avatar}}\" alt=\"Generic placeholder image\">\r\n    <div class=\"media-body\">\r\n        <h5 class=\"mt-0\">{{name.findName}}<\/h5>\r\n        {{lorem.paragraph}}\r\n    <\/div>\r\n<\/div>\r\n\r\n",
    "media\/list": "\r\n<ul class=\"list-unstyled\">\r\n    <li class=\"media\">\r\n        <img class=\"mr-3\" src=\"{{image.avatar}}\" alt=\"Generic placeholder image\">\r\n        <div class=\"media-body\">\r\n            <h5 class=\"mt-0 mb-1\">{{name.findName}}<\/h5>\r\n            {{lorem.paragraph}}\r\n        <\/div>\r\n    <\/li>\r\n    <li class=\"media my-4\">\r\n        <img class=\"mr-3\" src=\"{{image.avatar}}\" alt=\"Generic placeholder image\">\r\n        <div class=\"media-body\">\r\n            <h5 class=\"mt-0 mb-1\">{{name.findName}}<\/h5>\r\n            {{lorem.paragraph}}\r\n        <\/div>\r\n    <\/li>\r\n    <li class=\"media\">\r\n        <img class=\"mr-3\" src=\"{{image.avatar}}\" alt=\"Generic placeholder image\">\r\n        <div class=\"media-body\">\r\n            <h5 class=\"mt-0 mb-1\">{{name.findName}}<\/h5>\r\n            {{lorem.paragraph}}\r\n        <\/div>\r\n    <\/li>\r\n<\/ul>\r\n\r\n\r\n",
    "media\/nested": "\r\n\r\n<div class=\"media\">\r\n    <img class=\"mr-3\" src=\"{{image.avatar}}\" alt=\"Generic placeholder image\">\r\n    <div class=\"media-body\">\r\n        <h5 class=\"mt-0\">{{name.findName}}<\/h5>\r\n        {{lorem.paragraph}}\r\n\r\n        <div class=\"media mt-3\">\r\n            <a class=\"pr-3\" href=\"#\">\r\n                <img src=\"{{image.avatar}}\" alt=\"Generic placeholder image\">\r\n            <\/a>\r\n            <div class=\"media-body\">\r\n                <h5 class=\"mt-0\">{{name.findName}}<\/h5>\r\n                {{lorem.paragraph}}\r\n            <\/div>\r\n        <\/div>\r\n    <\/div>\r\n<\/div>\r\n\r\n",
    "media\/right": "\r\n<div class=\"media\">\r\n    <div class=\"media-body\">\r\n        <h5 class=\"mt-0 mb-1\">{{name.findName}}<\/h5>\r\n        {{lorem.paragraph}}\r\n    <\/div>\r\n    <img class=\"ml-3\" src=\"{{image.avatar}}\" alt=\"Generic placeholder image\">\r\n<\/div>\r\n",
    "pages\/forget_password": "<form class=\"form-horizontal\" role=\"form\" method=\"POST\" action=\"\">\r\n\r\n    <div class=\"form-group\">\r\n        <label for=\"email\" class=\"\">E-Mail Address<\/label>\r\n\r\n        <div class=\"\">\r\n            <input id=\"email\" type=\"email\" class=\"form-control\" name=\"email\" value=\"\" placeholder=\"e.g. demo@example.com\" required>\r\n        <\/div>\r\n    <\/div>\r\n\r\n    <div class=\"form-group\">\r\n        <div class=\"\">\r\n            <button type=\"submit\" class=\"btn btn-primary\">\r\n                Send Password Reset Link\r\n            <\/button>\r\n        <\/div>\r\n    <\/div>\r\n<\/form>",
    "pages\/login": "<form class=\"form-horizontal\" role=\"form\" method=\"POST\" action=\"\">\r\n\r\n    <div class=\"form-group\">\r\n        <label class=\"form-control-label\" for=\"email\" class=\"\">E-Mail Address<\/label>\r\n        <div class=\"input-group\">\r\n            <div class=\"input-group-addon\">\r\n                <i class=\"fa fa-envelope\"><\/i>\r\n            <\/div>\r\n            <input id=\"email\" type=\"email\" class=\"form-control\"\r\n                   name=\"email\" value=\"\"\r\n                   placeholder=\"e.g. demo@example.com\" required\r\n                   autofocus>\r\n        <\/div>\r\n\r\n    <\/div>\r\n\r\n    <div class=\"form-group\">\r\n        <label class=\"form-control-label\" for=\"password\">Password<\/label>\r\n\r\n        <div class=\"input-group\">\r\n            <div class=\"input-group-addon\">\r\n                <i class=\"fa fa-lock\"><\/i>\r\n            <\/div>\r\n            <input id=\"password\" type=\"password\" class=\"form-control\" placeholder=\"Your account password\"\r\n                   name=\"password\" required>\r\n        <\/div>\r\n    <\/div>\r\n\r\n    <div class=\"form-group\">\r\n        <div class=\"checkbox\">\r\n            <label>\r\n                <input type=\"checkbox\" name=\"remember\"> Remember Me\r\n            <\/label>\r\n        <\/div>\r\n    <\/div>\r\n\r\n    <div class=\"form-group\">\r\n        <button type=\"submit\" class=\"btn btn-primary\">\r\n            Login\r\n        <\/button>\r\n    <\/div>\r\n    <div class=\"form-group\">\r\n        <p>\r\n            <a class=\"btn btn-link\" href=\"#\">\r\n                Forgot Your Password?\r\n            <\/a>\r\n            <a class=\"btn btn-link\" href=\"#\">\r\n                Register\r\n            <\/a>\r\n        <\/p>\r\n\r\n\r\n    <\/div>\r\n<\/form>\r\n",
    "pages\/register": "<div class=\"row\">\r\n    <div class=\"col-md-6\">\r\n        <div class=\"form-group\">\r\n\r\n            <label for=\"first_name\" class=\"control-label\">First Name<\/label>\r\n            <input id=\"first_name\" type=\"text\" class=\"form-control\" name=\"first_name\"\r\n                   value=\"\" placeholder=\"e.g. John\" required\r\n                   autofocus>\r\n        <\/div>\r\n    <\/div>\r\n    <div class=\"col-md-6\">\r\n        <div class=\"form-group\">\r\n            <label for=\"last_name\" class=\"control-label\">Last Name<\/label>\r\n\r\n            <input id=\"last_name\" type=\"text\" class=\"form-control\" name=\"last_name\"\r\n                   value=\"\" placeholder=\"e.g. Doe\">\r\n\r\n        <\/div>\r\n    <\/div>\r\n\r\n<\/div>\r\n<div class=\"row\">\r\n    <div class=\"col-sm-6\">\r\n        <div class=\"form-group\">\r\n            <label for=\"email\" class=\"control-label\">E-Mail Address<\/label>\r\n\r\n            <input id=\"email\" type=\"email\" class=\"form-control\" name=\"email\"\r\n                   value=\"\" placeholder=\"e.g. demo@example.com\" required>\r\n\r\n        <\/div>\r\n    <\/div>\r\n    <div class=\"col-sm-6\">\r\n        <div class=\"form-group\">\r\n            <label for=\"phone\" class=\"control-label\">Phone<\/label>\r\n            <input type=\"text\" class=\"form-control\" id=\"phone\" name=\"phone\" value=\"\"\r\n                   placeholder=\"E.g. +460001000\">\r\n        <\/div>\r\n    <\/div>\r\n<\/div>\r\n\r\n<div class=\"row\">\r\n    <div class=\"col-md-6\">\r\n        <div class=\"form-group\">\r\n            <label for=\"password\" class=\"\">Password<\/label>\r\n            <input id=\"password\" type=\"password\" placeholder=\"min 6 character\" class=\"form-control\" name=\"password\"\r\n            >\r\n        <\/div>\r\n    <\/div>\r\n    <div class=\"col-md-6\">\r\n        <div class=\"form-group\">\r\n            <label for=\"password-confirm\" class=\"\">Confirm Password<\/label>\r\n            <input id=\"password-confirm\" type=\"password\" placeholder=\"Write again\" class=\"form-control\"\r\n                   name=\"password_confirmation\">\r\n        <\/div>\r\n    <\/div>\r\n<\/div>\r\n<div class=\"form-row\">\r\n    <div class=\"form-group col\">\r\n        <label for=\"address\">Address<\/label>\r\n        <div class=\"input-group\">\r\n            <div class=\"input-group-addon\">\r\n                <a title=\"Get your current address  by click here.\" href=\"#\"><i class=\"fa fa-map-marker\"><\/i><\/a>\r\n            <\/div>\r\n            <input type=\"text\" class=\"form-control address\" name=\"address\" value=\"\"\r\n                   placeholder=\"Your current address\" id=\"address\">\r\n\r\n        <\/div>\r\n\r\n    <\/div>\r\n    <div class=\"form-group col\">\r\n        <label for=\"phone\">Phone Number<\/label>\r\n        <div class=\"input-group\">\r\n            <div class=\"input-group-addon\">\r\n                <i class=\"fa fa-mobile\"><\/i>\r\n            <\/div>\r\n            <input type=\"text\" class=\"form-control address\" name=\"phone\" value=\"\"\r\n                   placeholder=\"e.g. +88019...\" id=\"phone\">\r\n\r\n        <\/div>\r\n\r\n    <\/div>\r\n<\/div>\r\n\r\n\r\n",
    "pages\/reset password": "<form class=\"form-horizontal\" role=\"form\" method=\"POST\" action=\"\">\r\n\r\n    <div class=\"form-group\">\r\n        <label for=\"password\" class=\"\">Current Password<\/label>\r\n\r\n        <div class=\"\">\r\n            <input id=\"current_password\" type=\"password\" class=\"form-control\" name=\"current_password\" required>\r\n        <\/div>\r\n    <\/div>\r\n\r\n    <div class=\"form-group\">\r\n        <label for=\"password\" class=\"\">Password<\/label>\r\n        <div class=\"\">\r\n            <input id=\"password\" type=\"password\" class=\"form-control\" name=\"password\" required>\r\n        <\/div>\r\n    <\/div>\r\n\r\n    <div class=\"form-group\">\r\n        <label for=\"password-confirm\" class=\"\">Confirm Password<\/label>\r\n        <div class=\"\">\r\n            <input id=\"password-confirm\" type=\"password\" class=\"form-control\" name=\"password_confirmation\" required>\r\n\r\n        <\/div>\r\n    <\/div>\r\n\r\n    <div class=\"form-group text-right\">\r\n\r\n        <button type=\"submit\" class=\"btn btn-primary\">\r\n            Reset Password\r\n        <\/button>\r\n    <\/div>\r\n<\/form>",
    "pages\/search_box": "<form class=\"form-inline\">\r\n    <input class=\"form-control mr-sm-2\" type=\"search\" placeholder=\"Search\" aria-label=\"Search\">\r\n    <button class=\"btn btn-outline-success my-2 my-sm-0\" type=\"submit\">Search<\/button>\r\n<\/form>"
};
var bootstrapToolbars = ['cards', 'components', 'Div', 'forms', 'media', 'pages'];
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
        'cards': function (context) {

            return addDropdown(context, 'cards', Object.values({
                "3_users": "3_users",
                "4_products": "4_products",
                "Header_footer": "header_footer",
                "Image_title": "image_title",
                "Title_link": "title_link"
            }), bootstrapTemplateContents);

        },
        'components': function (context) {

            return addDropdown(context, 'components', Object.values({
                "Blockquote": "blockquote",
                "Carousel": "carousel",
                "Collapse": "collapse",
                "Dropdown": "dropdown",
                "Jumbotron": "jumbotron",
                "List_group": "list_group",
                "Modal": "modal",
                "Navbar": "navbar",
                "Nav_tabs": "nav_tabs",
                "Pagination": "pagination"
            }), bootstrapTemplateContents);

        },
        'Div': function (context) {

            var content = bootstrapTemplateContents['div']

            return addMenu(context, 'Div', content);

        },
        'forms': function (context) {

            return addDropdown(context, 'forms', Object.values({
                "Checkbox": "checkbox",
                "Custom_checkbox": "custom_checkbox",
                "Date": "date",
                "DateTime": "dateTime",
                "Email": "email",
                "File": "file",
                "Number": "number",
                "Password": "password",
                "Radio": "radio",
                "Search": "search",
                "Select": "select",
                "Submit": "submit",
                "Text": "text",
                "Time": "time",
                "Url": "url"
            }), bootstrapTemplateContents);

        },
        'media': function (context) {

            return addDropdown(context, 'media', Object.values({
                "Default": "default",
                "List": "list",
                "Nested": "nested",
                "Right": "right"
            }), bootstrapTemplateContents);

        },
        'pages': function (context) {

            return addDropdown(context, 'pages', Object.values({
                "Forget_password": "forget_password",
                "Login": "login",
                "Register": "register",
                "Reset password": "reset password",
                "Search_box": "search_box"
            }), bootstrapTemplateContents);

        },

    });
}));

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
        if (typeof img.caption === 'undefined' || img.caption != null) {
            img.caption = '';
        }
        figureHtml += '<label for="imageGalleryCheckbox_' + i + '">' +
            '<input type="checkbox" name="insertGalleryImages" class="insertGalleryImagesCheckbox" id="imageGalleryCheckbox_' + i + '" value="' + img.url + '" >' +
            '<img src="' + img.thumbnail + '" class="figure-img img-fluid rounded" width="100px" title="' + img.caption + '"/></label>' +

            '\n';
        if (img.caption != null && img.caption.length > 0) {
            var caption = img.caption;
            if (caption.length > 15) {
                caption = img.caption.substr(0, 15) + '...';
            }
            figureHtml += '<figcaption class="figure-caption text-center">' + caption + '</figcaption>\n';
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
        'fa fa-clock', 'fa fa-cloud', 'fa fa-tags', 'fa fa-comment', 'fa fa-comments', 'fa fa-send', 'fa fa-share', 'fa fa-mail-forward', 'fa fa-thumbs-up', 'fa fa-thumbs-down',
        'fa fa-mobile', 'fa fa-phone',
    ],
    'e': ['fa fa-navicon', 'fa fa-map', 'fa fa-map-marker', 'fa fa-map-pin', 'fa fa-file-zip-o', 'fa fa-file-text-o', 'fa fa-file-pdf-o', 'fa fa-file-excel-o', 'fa fa-file-word-o',
        'fa fa-file-video-o', 'fa fa-audio-o', 'fa fa-file-code-o', 'fa fa-car', 'fa fa-bus', 'fa fa-cab', 'fa fa-bicycle'],
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

$(document).ready(function (e) {

    var iconClasses = [];
    var iconKeys = Object.keys(fontAwesomeIcons);
    for (var i = 0; i < iconKeys.length; i++) {
        var key = iconKeys[i];
        iconClasses = iconClasses.concat(fontAwesomeIcons[key]);
    }

    $('.note-editable').textcomplete([
        {
            match: /:(\w*)$/,
            search: function (term, callback) {
                callback($.map(iconClasses, function (element) {
                    return element.indexOf(term) !== -1 ? element : null;
                }));
            },
            index: 1,
            template: function (value) {
                return '<i class=" ' + value + '"></i>' + value;
            },
            replace: function (element) {
                var name = '<i class=" ' + element + '"></i>';
                return [name, ''];
            }
        },
        { // theme faker field
            match: /#(\w*)$/,
            search: function (term, callback) {
                callback($.map(internalLinks, function (element) {
                    return element.indexOf(term) !== -1 ? element : null;
                }));
            },
            index: 1,
            template: function (value) {
                return '<i class=" ' + value + '"></i>' + value;
            },
            replace: function (element) {
                var elArr = element.split("/");
                if (elArr.length > 1) {
                    var disPlayName = elArr.pop();
                } else {
                    var disPlayName = element;
                }
                var name = '<a  href=" ' + filePreviewUrl + '?path=' + element + '">' + disPlayName + '</a>';
                return [name, ''];
            }
        }
    ]);
})

$(".bootstrap-summernote-editor").summernote({
    callbacks: {
        onImageUpload: function (files) {
            var $this = $(this);
            sendFile(files[0], function (url) {
                $this.summernote("insertImage", url);
            });
        },
    },
    tabsize: 2,
    enterHtml: "<br/>",
    gallery: {
        url: '/api/photo/photos',
    },
    height: 600,
    toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear', 'tags']],
        ['fontname', ['fontname']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['icons', ['icons']],
        ['bootstrapColors', ['texts', 'inputButtons', 'badges', 'bg-color']],
        ['view', ['fullscreen', 'codeview', 'help']],
        ['modal', ['tooltip', 'popover', 'gallery']],
        ['rows', ['colsm', 'colmd', 'collg', 'colxs']],
    ],
});
