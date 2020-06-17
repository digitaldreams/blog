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
