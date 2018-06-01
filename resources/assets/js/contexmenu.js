$(document).ready(function () {
    Array.prototype.diff = function (a) {
        return this.filter(function (i) {
            return a.indexOf(i) < 0;
        });
    };

    var clickedNode = false;
    var keys = Object.keys(BootstrapClasses);
    var bootStrapSelect2ClassOptions = [];
    var bootStrapClassList = [];
    var finalNodeToAddClass = false;

    for (var k = 0; k < keys.length; k++) {
        var keyName = keys[k];
        var childOptions = [];
        for (var ik = 0; ik < BootstrapClasses[keyName].length; ik++) {
            bootStrapClassList.push(BootstrapClasses[keyName][ik]);

            childOptions.push({
                id: BootstrapClasses[keyName][ik],
                text: BootstrapClasses[keyName][ik]
            })
        }
        bootStrapSelect2ClassOptions.push({
            text: keyName,
            children: childOptions
        });
    }

    $("#addClassesToNode").select2({
        data: bootStrapSelect2ClassOptions
    });

    function camelCaseToLabel(item) {
        var names = item.split(".");
        if (names.length > 1) {
            var name = names[names.length - 1];
        } else {
            var name = item
        }
        return name.replace(/([A-Z])/g, ' $1')
            .replace(/^./, function (str) {
                return str.toUpperCase();
            })
    }

    function randomNumber(min, max) {
        return Math.floor(Math.random() * (parseInt(max) - parseInt(min) + parseInt(1))) + parseInt(min);
    }

    function random_rgba(a) {
        var o = Math.round, r = Math.random, s = 255;
        return 'rgba(' + o(r() * s) + ',' + o(r() * s) + ',' + o(r() * s) + ',' + a + ')';
    }


    function formatState(state) {
        if (!state.id) {
            return state.text;
        }
        var $state = $(
            '<span><img src="' + state.element.value.toLowerCase() + '" class="img-flag" width="80px" /> ' + state.text + '</span>'
        );
        return $state;
    };

    function string_to_slug(str) {
        str = str.replace(/^\s+|\s+$/g, ''); // trim
        str = str.toLowerCase();

        // remove accents, swap ñ for n, etc
        var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
        var to = "aaaaeeeeiiiioooouuuunc------";
        for (var i = 0, l = from.length; i < l; i++) {
            str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
        }

        str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
            .replace(/\s+/g, '-') // collapse whitespace and replace by -
            .replace(/-+/g, '-'); // collapse dashes

        return str;
    }

    var list = FakerFields;
    var keys = Object.keys(list);
    var html = '';
    var fieldOptions = [];
    for (i = 0; i < keys.length; i++) {
        var items = list[i];

        for (var k = 0; k < items.length; k++) {
            var item = items[k];

            fieldOptions.push({
                id: item,
                text: camelCaseToLabel(item)
            });
        }

    }

    $("#insertTableFields").select2({
        data: fieldOptions,
        width: '100%'
    });

    $("#insertTabsFields").select2({
        width: '100%',
        tags: true
    });
    $("#insertChartjsDataTypeFields").select2({
        width: '100%',
        tags: true
    });

    function getAndSetClass(clickedNode) {
        var selectedClasList = [];
        var eClsses = clickedNode.classList;
        for (var ec = 0; ec < eClsses.length; ec++) {
            if (bootStrapClassList.includes(eClsses.item(ec))) {
                selectedClasList.push(eClsses.item(ec));
            }
        }
        $("#addClassesToNode").val(selectedClasList);
        $('#addClassesToNode').trigger('change');
    }

    $('body').on('click', '.note-editing-area', function (e) {
        // e.stopPropagation();
        // e.preventDefault();
        clickedNode = e.target;
        finalNodeToAddClass = e.target;
        getAndSetClass(clickedNode);

        if (e.target.nodeName == 'INPUT') {
            $("#nav-input-tab").trigger('click');
            var input = {
                placeholder: clickedNode.getAttribute('placeholder'),
                name: clickedNode.getAttribute('name'),
                read: clickedNode.hasAttribute('readonly'),
                required: clickedNode.hasAttribute('required'),
                min: clickedNode.getAttribute('min'),
                max: clickedNode.getAttribute('max')
            }

            $("#inputPlaceholder").val(input.placeholder);
            $("#inputTagName").val(input.name);
            $("#inputMinAttr").val(input.min);
            $("#inputMaxAttr").val(input.max);

            document.getElementById("inputIsReadOnly").checked = input.read;
            document.getElementById('inputIsRequired').checked = input.required;

            $('#nav-input-tab').show();
        } else {
            $("#nav-bootstrapClassList-tab").trigger('click');
            $('#nav-input-tab').hide();
        }

        var mouseX = e.pageX;
        var mouseY = e.pageY;

        $("#currentlySelectedNode").text(clickedNode.nodeName + '(' + clickedNode.classList + ')');
        document.getElementById('currentlySelectedNodeOption').checked = true;

        $("#currentlySelectedNodeParent").text(clickedNode.parentElement.nodeName + '(' + clickedNode.parentElement.classList + ')');
        //currentlySelectedNodeGrandParent
        if (clickedNode.parentElement.parentElement) {
            $("#currentlySelectedNodeGrandParent").text(clickedNode.parentElement.parentElement.nodeName + '(' + clickedNode.parentElement.parentElement.classList + ')');
        }
        //  $("#customContextMenu").toggle();
    });

    $('input[type=radio][name=currentlySelectedNode]').on('change', function () {
        switch ($(this).val()) {
            case 'self':
                finalNodeToAddClass = clickedNode;
                getAndSetClass(finalNodeToAddClass);
                break;
            case 'parent':
                finalNodeToAddClass = clickedNode.parentElement;
                getAndSetClass(finalNodeToAddClass);
                break;
            case 'grandParent':
                finalNodeToAddClass = clickedNode.parentElement.parentElement;
                getAndSetClass(finalNodeToAddClass);
                break;
        }
    });

    $('body').on('click', '#addBootStrapClassBtn', function (e) {
        var classes = $('#addClassesToNode').val();

        var selectedClassList = [];
        var eClsses = finalNodeToAddClass.classList;
        for (var ec = 0; ec < eClsses.length; ec++) {
            if (bootStrapClassList.includes(eClsses.item(ec))) {
                selectedClassList.push(eClsses.item(ec));
            }
        }
        var deleteCls = selectedClassList.diff(classes);
        for (var dc = 0; dc < deleteCls.length; dc++) {
            finalNodeToAddClass.classList.remove(deleteCls[dc]);
        }

        var addCls = classes.diff(selectedClassList);
        for (var ac = 0; ac < addCls.length; ac++) {
            finalNodeToAddClass.classList.add(addCls[ac]);
        }

        // $("#customContextMenu").hide();
    });

    $(document).on('click', '#insertTableModalSaveBtn', function (e) {

        var fields = $("#insertTableFields").val();
        var tableHtml = '\n<table class="table table-hover table-responsive">';
        var headerHtml = '\n\t<thead>\n' +
            '\t\t<tr>\n';
        var bodyHtml = '\n\t<tbody>\n';
        var total = parseInt($('#totalTableRow').val());

        for (var f = 0; f < fields.length; f++) {
            headerHtml += '\n\t\t<th>' + camelCaseToLabel(fields[f]) + '</th>\n';
        }

        headerHtml += '\n\t\t</tr>\n' +
            '\n\t</thead>\n';

        for (var i = 0; i < total; i++) {
            var tr = '\n\t\t<tr>';
            for (var fd = 0; fd < fields.length; fd++) {
                text = faker.fake("{{" + fields[fd] + "}}");
                tr += '\n\t\t\t<td>' + text + '</td>';
            }
            tr += '\n\t\t</tr>\n';
            bodyHtml += tr;
        }
        bodyHtml += '\t</tbody>\n';
        tableHtml += headerHtml;
        tableHtml += bodyHtml;
        tableHtml += '</table>';

        var temp = document.createElement('div');
        temp.innerHTML = tableHtml;
        while (temp.firstChild) {
            clickedNode.appendChild(temp.firstChild);
        }
        $("#insertTableFields").val(null).trigger('change');
        // $("#customContextMenu").hide();
    });

    $(document).on('click', '#removeCurrentlySelectedNode', function (e) {
        finalNodeToAddClass.remove();
    });

    $(document).on('click', '#cloneCurrenltySelectedNode', function (e) {
        var clonedNode = finalNodeToAddClass.cloneNode(true);
        //  finalNodeToAddClass.appendChild(clonedNode);
        finalNodeToAddClass.parentNode.insertBefore(clonedNode, finalNodeToAddClass.nextSibling);

    });

    $(document).on('click', '#insertTabsModalSaveBtn', function (e) {
        var randNumber = Math.floor(Math.random() * 20);
        var fields = $("#insertTabsFields").val();
        var tableHtml = '\n<div>';
        var headerHtml = '\n\t<nav>\n\t\t<div class="nav nav-tabs" id="nav-tab_' + randNumber + '" role="tablist">\n';
        var bodyHtml = '\n\t<div class="tab-content" id="nav-tabContent_' + randNumber + '">\n';

        for (var f = 0; f < fields.length; f++) {
            //
            if (f == 0) {
                var active = ' active';
            } else {
                var active = '';
            }

            headerHtml += '\n\t\t <a class="nav-item nav-link ' + active + '" id="nav-' + string_to_slug(fields[f]) + '-tab" ' +
                'data-toggle="tab" href="#nav-' + string_to_slug(fields[f]) + '" role="tab" aria-controls="nav-' + string_to_slug(fields[f]) + '" aria-selected="true">' + fields[f] + '</a>\n';
        }

        headerHtml += '\n\t\t</div>\n' +
            '\n\t</nav>\n';

        for (var i = 0; i < fields.length; i++) {
            if (i == 0) {
                var active = ' show active';
            } else {
                var active = '';
            }
            bodyHtml += '\n\t\t<div class="tab-pane fade ' + active + '" id="nav-' + string_to_slug(fields[i]) + '" role="tabpanel" aria-labelledby="nav-' + string_to_slug(fields[i]) + '-tab">Content for ' + fields[i] + '</div>';

        }
        bodyHtml += '\n\t</div>\n';
        tableHtml += headerHtml;
        tableHtml += bodyHtml;
        tableHtml += '</div>\n';

        var temp = document.createElement('div');
        temp.innerHTML = tableHtml;
        while (temp.firstChild) {
            clickedNode.appendChild(temp.firstChild);
        }
        $("#insertTabsFields").val(null).trigger('change');
        //  $("#customContextMenu").hide();
    });

    $(document).on('click', '#insertInputTabSaveBtn', function (e) {

        var input = {
            placeholder: $("#inputPlaceholder").val(),
            name: $("#inputTagName").val(),
            read: document.getElementById("inputIsReadOnly").checked,
            required: document.getElementById('inputIsRequired').checked,
            min: $("#inputMinAttr").val(),
            max: $("#inputMaxAttr").val()
        }

        if (input.read) {
            if (!clickedNode.hasAttribute('readonly')) {
                clickedNode.setAttribute('readonly', true)
            }
        } else {
            if (clickedNode.hasAttribute('readonly')) {
                clickedNode.removeAttribute('readonly');
            }
        }

        if (input.required) {
            if (!clickedNode.hasAttribute('required')) {
                clickedNode.setAttribute('required', true)
            }
        } else {
            if (clickedNode.hasAttribute('required')) {
                clickedNode.removeAttribute('required');
            }
        }
        clickedNode.setAttribute('placeholder', input.placeholder);

        clickedNode.setAttribute('min', input.min);

        clickedNode.setAttribute('max', input.max);

        clickedNode.setAttribute('name', input.name);

        // $("#customContextMenu").hide();
    });


    $(document).on('click', '#insertChartjsSaveBtn', function (e) {

        var type = $("#insertChartjsType").val();
        var dataType = $("#insertChartjsDataTypeFields").val();
        var label = $("#insertChartjsLablesFields").val();
        var min = randomNumber(100, 500);
        var max = randomNumber(500, 5000);
        var title = $("#insertChartjsTitle").val();
        var dataset = [];
        var xLabels = [];


        switch (type) {
            case 'line':
            case 'bar':
                var total = randomNumber(5, 12);
                for (var dl = 1; dl < total; dl++) {
                    switch (label) {
                        case 'day':
                            xLabels.push(moment('April 01 2018', 'MMM DD YYYY').add(dl, 'd').format('MMM DD YYYY'));
                            break;
                        case 'week':
                            xLabels.push(moment('January 01 2018', 'MMM DD YYYY').add(dl, 'w').format('MMM DD YYYY'));

                            break;
                        case 'month':
                            xLabels.push(moment('January 01 2018', 'MMM DD YYYY').add(dl, 'M').format('MMM'));
                            break;
                        case 'year':
                            xLabels.push(moment('January 01 2013', 'MMM DD YYYY').add(dl, 'y').format('YYYY'));
                            break;
                        default:
                            xLabels.push(dl);
                            break;
                    }
                }

                for (var d = 0; d < dataType.length; d++) {
                    var set = {};
                    set.label = dataType[d];
                    set.backgroundColor = type == 'line' ? random_rgba(0.1) : random_rgba(0.8);
                    set.borderColor = random_rgba(0.8);
                    set.data = [];

                    for (var dt = 1; dt < total; dt++) {
                        set.data.push(randomNumber(min, max));
                    }
                    dataset.push(set);
                }
                break;
            case 'pie':
            case 'doughnut':
                var xLabels = [];
                var set = {};
                set.backgroundColor = [];
                set.borderColor = random_rgba(1);
                set.data = [];
                for (var d = 0; d < dataType.length; d++) {
                    xLabels.push(dataType[d]);
                    set.data.push(randomNumber(min, max));
                    set.backgroundColor.push(random_rgba(0.7));
                }
                dataset.push(set);
                break;
        }
        var data = {
            type: type,
            data: {
                labels: xLabels,
                datasets: dataset
            },
            options: {
                responsive: false,
                animation: false,
            }
        }
        var title = $('#insertChartjsTitle').val();

        if (title.length > 1) {
            data.options.title = {
                display: true,
                text: title
            }
        }

        var canvas = document.createElement("canvas");
        canvas.width = clickedNode.offsetWidth;
        canvas.height = (parseInt(clickedNode.offsetWidth) / parseInt(100)) * parseInt(75);
        var ctx = canvas.getContext('2d');
        var myLineChart = new Chart(ctx, data);

        var dataUrl = canvas.toDataURL();

        var img = document.createElement("image");
        img.setAttribute('src', dataUrl);

        clickedNode.appendChild(img);
        $("#summernote").trigger('summernote.change');
        $("#insertChartjsDataTypeFields").val(null).trigger('change');
        $('#insertChartjsTitle').val('');
    })
});