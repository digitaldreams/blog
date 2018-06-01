(function ($) {

    $.fn.bootsum = function (options) {

        var settings = $.extend(true, {}, $.fn.bootsum.defaults, options);
        this.summernote(settings).on('summernote.change', function (we, contents, $editable) {
            $(this).html(contents);
        });
        return this;
    };
    $.fn.bootsum.defaults = {
        callbacks: {
            onImageUpload: function (files) {
                var $this = $(this);
                sendFile(files[0], function (url) {
                    $this.summernote("insertImage", url);
                });
            },
        },
        popover: {
            image: [
                ['custom', ['imageAttributes']],
                ['imagesize', ['imageSize100', 'imageSize50', 'imageSize25']],
                ['float', ['floatLeft', 'floatRight', 'floatNone']],
                ['remove', ['removeMedia']]
            ],
            link: [
                ['link', ['linkDialogShow', 'unlink']]
            ],
            table: [
                ['add', ['addRowDown', 'addRowUp', 'addColLeft', 'addColRight']],
                ['delete', ['deleteRow', 'deleteCol', 'deleteTable']]
            ],
            air: [
                ['color', ['color']],
                ['font', ['bold', 'underline', 'clear']],
                ['para', ['ul', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture']]
            ]
        },
        fakeTable: {
            fields: FakerFields
        },
        lang: 'en-US',
        imageAttributes: {
            icon: '<i class="note-icon-pencil"/>',
            removeEmpty: false, // true = remove attributes | false = leave empty if present
            disableUpload: false // true = don't display Upload Options | Display Upload Options
        },
        gallery: {
            url: prototypeGalleryUrl
        },
        tabsize: 2,
        enterHtml: "<br/>",
        codemirror: {
            htmlMode: true,
            theme: 'dracula',
            mode: "text/html",
            lineNumbers: true,
            tabMode: 'indent'
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
            ['fake', ['fakerPlugin']]
        ],
    }
}(jQuery));