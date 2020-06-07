@include('blog::includes.tooltipModal')
@include('blog::includes.popoverModal')
@include('blog::includes.summernoteImageInsertModal')

<script src="<?= asset('blog/js/select2.full.min.js') ?>" type="text/javascript"></script>

<script src="<?= asset('blog/codemirror/js/codemirror.min.js') ?>" type="text/javascript"></script>
<script src="<?= asset('blog/codemirror/js/xml.min.js') ?>" type="text/javascript"></script>
<script src="<?= asset('blog/codemirror/js/formatting.min.js') ?>" type="text/javascript"></script>


<script src="<?= asset('blog/js/editor_functions.js') ?>"></script>
<script src="<?= asset('blog/summernote/plugins/component-functions.js') ?>"></script>
<script src="<?= asset('blog/summernote/summernote-bs4.js') ?>"></script>
<script src="<?= asset('blog/summernote/plugins/icons.js') ?>"></script>
<script src="<?= asset('blog/summernote/plugins/gallery.js') ?>"></script>
<script src="<?= asset('blog/js/bootstrap-classes.js') ?>"></script>
<script src="<?= asset('blog/summernote/plugins/faker.js') ?>"></script>
<script src="<?= asset('blog/summernote/plugins/tags.js') ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.textcomplete/1.8.4/jquery.textcomplete.min.js"></script>

<script src="<?= asset('blog/js/contexmenu.js') ?>"></script>
<script src="<?= asset('blog/js/voice.js') ?>"></script>
<script src="<?= asset('blog/summernote/plugins/summernote-image-attributes/summernote-image-attributes.js') ?>"
        type="text/javascript"></script>
<script src="<?= asset('blog/js/bootsum.js" type="text/javascript') ?>"></script>

<script type="text/javascript">

    $(document).ready(function (e) {
        window.templateContents = [];
        $.fn.bootsum.defaults.toolbar.push(['{{request()->route('project')->key}}',{{request()->route('project')->template->getPluginInfo('toolbar')}} ]);
        $("#summernote").bootsum({});
    })
</script>

<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="popover"]').popover({
            html: true
        });
    })
</script>

<script type="text/javascript">
    $('#manageFolderModal').on('shown.bs.modal', function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        var path = button.data('path') // Extract info from data-* attributes
        $("#manageFolderModalTitle").text(path);
        $("#pathToSave").val(path);

    });
</script>
<script type="text/javascript">
    $(document).ready(function (e) {
        var filePreviewUrl = '{{route('prototype::projects.resources.preview',request()->route('project')->key)}}';
        var fakerColumns = [];
        var iconClasses = [];
        var internalLinks =
                {!! json_encode((new Prototype\Services\Template(request()->route('project')->path))->run(false)->paths(),JSON_UNESCAPED_SLASHES) !!}

        for (var f = 0; f < FakerFields.length; f++) {
            var col = FakerFields[f];
            if (Array.isArray(col)) {
                fakerColumns = fakerColumns.concat(col);
            } else {
                fakerColumns.push(FakerFields[f]);
            }
        }
        var iconKeys = Object.keys(IconList);
        for (var i = 0; i < iconKeys.length; i++) {
            var key = iconKeys[i];
            iconClasses = iconClasses.concat(IconList[key]);
        }
        $('.note-editable').textcomplete([
            { // theme faker field
                match: /@{{(\w*)$/,
                search: function (term, callback) {
                    callback($.map(fakerColumns, function (element) {
                        return element.indexOf(term) !== -1 ? element : null;
                    }));
                },
                index: 1,
                replace: function (element) {
                    return ['{{' + element + '}}', ''];
                }
            },
            { // theme faker field
                match: /@(\w*)$/,
                search: function (term, callback) {
                    callback($.map(fakerColumns, function (element) {
                        return element.indexOf(term) !== -1 ? element : null;
                    }));
                },
                index: 1,
                replace: function (element) {
                    var name = faker.fake('@{{' + element + '}}');
                    return [name, ''];
                }
            },
            { // theme faker field
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

</script>