// Hide submenus
$('#body-row .collapse').collapse('hide');

// Collapse/Expand icon
$('#collapse-icon').addClass('fa-angle-double-left');

// Collapse click
$('[data-toggle=sidebar-colapse]').click(function () {
    SidebarCollapse();
});

function SidebarCollapse() {
    $('.menu-collapsed').toggleClass('d-none');
    $('.sidebar-submenu').toggleClass('d-none');
    $('.submenu-icon').toggleClass('d-none');
    $('#sidebar-container').toggleClass('sidebar-expanded sidebar-collapsed');
    $('#sidebar-container').toggleClass('d-none');
    // Treating d-flex/d-none on separators with title
    var SeparatorTitle = $('.sidebar-separator-title');
    if (SeparatorTitle.hasClass('d-flex')) {
        SeparatorTitle.removeClass('d-flex');
    } else {
        SeparatorTitle.addClass('d-flex');
    }

    // Collapse/Expand icon
    $('#collapse-icon').toggleClass('fa-angle-double-left fa-angle-double-right');
}

function checkSize(max_img_size, id) {
    var input = document.getElementById(id);
    var allowedImageMimeType = [
        'image/svg+xml',
        'image/jpg',
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/bmp',
        'image/webp'
    ];

    if (input.files && input.files.length == 1) {
        if (allowedImageMimeType.indexOf(input.files[0].type) == -1) {
            alert('File Type Not allowed. Only jpg,jpeg,png,webp,svg allowed');
            input.value = '';
            return false;
        }
        if (input.files[0].size > max_img_size) {
            var yourFileSize = (input.files[0].size / 1024 / 1024);
            input.value = '';
            alert("The file must be less than " + (max_img_size / 1024 / 1024) + "MB", "Your file size is " + yourFileSize.toFixed(2) + 'MB', "warning")
            return false;
        } else {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#' + id + '_preview').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
    return true;
}

$(document).ready(function () {

    var clickedNode = null;
    $('body').on('click', '.note-editing-area,textarea,input', function (e) {
        clickedNode = e.target;
    });
    var recognition = null;
    var contents = {};


    function startDictation(btn) {
        var lang = $("#voiceCommandLanguage").val() || 'bn-BD';

        if (window.hasOwnProperty('webkitSpeechRecognition')) {
            if (!recognition) {
                recognition = new webkitSpeechRecognition();
            } else {
                recognition.stop();
                recognition = null;
                return false;
            }

            recognition.continuous = true;
            recognition.interimResults = false;

            recognition.lang = lang;
            recognition.start();
            $(btn).removeClass('text-gray').addClass('text-primary');
            recognition.onresult = function (e) {
                var lastIndex = parseInt(e.results.length) - parseInt(1);
                var lastScript = e.results[lastIndex];

                var endChar = lang == 'bn-BD' ? ' |' : '.';

                if (clickedNode) {
                    if (clickedNode.nodeName == 'INPUT' || clickedNode.nodeName == 'TEXTAREA') {
                        clickedNode.value = clickedNode.value +' '+ lastScript[0].transcript + endChar;
                    } else {
                        var text = document.createTextNode(lastScript[0].transcript + endChar);
                        clickedNode.appendChild(text);
                    }
                }

            };
            recognition.onend = function () {
                $("#voiceCommandMessage").text('Completed');
            };
            recognition.onsoundstart = function (e) {
                $("#voiceCommandMessage").text('Listening...');
            }
            recognition.onsoundend = function (e) {
                $("#voiceCommandMessage").text('Sleeping...');
            }
            recognition.onspeechend = function (e) {
                $("#voiceCommandMessage").text('Speech Ended');
                $(btn).removeClass('text-primary').addClass('text-gray');
            }

            recognition.onerror = function (e) {
                recognition.stop();
            }

        } else {
            console.log('Opps sorry your browser does not support Speech Recognition')
        }
    }

    $("#initVoiceRecognitionCommand").on('click', function (e) {
        startDictation(this);
    });
});

$(function () {
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover({
        html: true
    });
});
