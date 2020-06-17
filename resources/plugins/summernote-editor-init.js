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
