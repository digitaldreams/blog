$(document).ready(function () {

    var clickedNode = null;
    $('body').on('click', '.note-editing-area', function (e) {
        clickedNode = e.target;
    });
    var recognition = null;
    var contents = {};
    var commands = [];


    function startDictation(btn) {
        var lang = $("#voiceCommandLanguage").val();
        for (var c = 0; c < window.templateContents.length; c++) {
            var con = window.templateContents[c];
            var objectKeys = Object.keys(con);
            for (var k = 0; k < objectKeys.length; k++) {
                contents[objectKeys[k].replace("/", "_")] = con[objectKeys[k]];
                commands.push(objectKeys[k].replace("/", "_"))
            }
        }

        //  console.log(window.templateContents);
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
                if (document.getElementById('voiceCommandControl').checked) {
                    var command = lastScript[0].transcript.toLowerCase().trim().replace(/ /g, "_");
                    if (contents.hasOwnProperty(command)) {
                        var e = document.createElement('div');
                        e.innerHTML = faker.fake(contents[command]);
                        while (e.firstChild) {
                            clickedNode.appendChild(e.firstChild);
                        }
                    } else if (commands.indexOf(command) !== -1) {
                        var originalCommand = commands[commands.indexOf(command)];
                        var e = document.createElement('div');
                        e.innerHTML = faker.fake(contents[originalCommand]);
                        while (e.firstChild) {
                            clickedNode.appendChild(e.firstChild);
                        }
                    } else {
                        var findCommand = commands.find(function (value) {
                            return (new RegExp(command, "i").test(value));
                        });
                        if (findCommand) {
                            var e = document.createElement('div');
                            e.innerHTML = faker.fake(contents[findCommand]);
                            while (e.firstChild) {
                                clickedNode.appendChild(e.firstChild);
                            }
                        } else {
                            console.log('Sorry we did not recognize your command [' + command + ']');
                        }
                    }
                } else {
                    var endChar = lang == 'bn-BD' ? ' |' : '.';
                    var text = document.createTextNode(lastScript[0].transcript + endChar);
                    if (clickedNode) {
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