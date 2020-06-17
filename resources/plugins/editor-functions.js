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
