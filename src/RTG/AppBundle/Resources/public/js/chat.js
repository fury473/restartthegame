var chat_conn = null;
var attempts = 1;

function details_in_popup(link, div_id){
    $.ajax({
        url: link,
        success: function(response){
            $('#'+div_id).html(response);
        }
    });
    return '<div id="'+ div_id +'"><div class="loader"></div></div>';
}

function fetchClients(data) {
    $('#chat-clients').html(data.html);
    $('a.popover-ajax')
    .blur()
    .click(function(e) { e.preventDefault(); })
    .popover({
        html: true,
        container: 'body',
        trigger: 'focus',
        content: function(){
            var div_id =  "tmp-id-" + $.now();
            return details_in_popup($(this).attr('href'), div_id);
        }
    });
}
function fetchNewMessage(data, old) {
    var messages = $('#chat-messages');
    var toBottom = false;
    if (isScrolledToBottom(messages)) {
        toBottom = true;
    }
    if (old) {
        messages.prepend(data.html);
    } else {
        messages.append(data.html);
    }
    if (toBottom) {
        scrollToBottom();
    }
}
function generateInterval(k) {
    var maxInterval = (Math.pow(2, k) - 1) * 1000;
    if (maxInterval > 30 * 1000) {
        maxInterval = 30 * 1000;
    }
    return Math.random() * maxInterval;
}
function isScrolledToBottom(element) {
    if (element[0].scrollHeight - element[0].scrollTop - element[0].clientHeight <= 25) {
        return true;
    }
    return false;
}
function scrollToBottom() {
    var messages = $('#chat-messages');
    messages.animate({scrollTop: messages[0].scrollHeight}, 'fast');
}
function startSocket(websocketServerLocation) {
    if (!window.WebSocket) {
        alert("Websocket is not supported by your browser.");
    }
    chat_conn = new WebSocket(websocketServerLocation);
    chat_conn.onclose = function (e) {
        chat_conn = null;
        $('#chat-connect-button').bootstrapToggle('off');
        toggleInterface(false);
        $('#chat-clients').html("");
        if (e.wasClean === false && e.code !== 1000) {
            $('#chat-connect-button').bootstrapToggle('disable');
            var time = generateInterval(attempts);
            var messages = $('#chat-messages');
            var prototype = messages.data('info-prototype');
            var reason = prototype.replace(/__name__/g, "La connexion au serveur est interrompue, reconnexion automatique dans " + (Math.ceil(time / 1000)) + " secondes.");
            var toBottom = false;
            if (isScrolledToBottom(messages)) {
                toBottom = true;
            }
            messages.append(reason);
            if (toBottom) {
                scrollToBottom();
            }
            setTimeout(function () {
                attempts++;
                startSocket(websocketServerLocation);
            }, time);
        }
    };
    chat_conn.onopen = function (e) {
        attempts = 1;
        if (!$('#chat-connect-button').prop('checked')) {
            $('#chat-connect-button').bootstrapToggle('enable');
            $('#chat-connect-button').bootstrapToggle('on');
        }
        toggleInterface(true);
        $('#chat-messages').html("");
        var user_id = $('#chat').data('user-id');
        var message = JSON.stringify({action: 'new_client', user: user_id});
        chat_conn.send(message);
    };
    chat_conn.onmessage = function (e) {
        $('#chat-connect-button').bootstrapToggle('disable');
        var data = JSON.parse(e.data);
        switch (data.action) {
            case 'banned':
                fetchNewMessage(data, false);
                chat_conn.close();
                break;
            case 'clients':
                fetchClients(data);
                break;
            case 'new_message':
                fetchNewMessage(data, false);
                break;
            case 'old_message':
                fetchNewMessage(data, true);
                break;
        }
        $('#chat-connect-button').bootstrapToggle('enable');
    };
}
function toggleInterface(enabled) {
    if (enabled) {
        $('#chat-message-edit textarea').removeAttr('disabled');
        $('#chat-message-edit button').removeAttr('disabled');
    } else {
        $('#chat-message-edit textarea').attr('disabled', 'disabled');
        $('#chat-message-edit button').attr('disabled', 'disabled');
    }
}
$(function () {
    $('#chat-message-edit').submit(function (e) {
        e.preventDefault();
        var form = $(this);
        var text = form.find("textarea").val();
        if (text === "" || !text.match(/.*\S+.*/)) {
            return false;
        }
        var message = JSON.stringify({action: 'new_message', text: text});
        chat_conn.send(message);
        form.find("textarea").val("");
    });
    $('#chat-message-edit textarea').keypress(function (e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#chat-message-edit button').click();
        }
    });
    $('#chat-connect-button').change(function () {
        var checked = $(this).prop('checked');
        if (checked) {
            if (chat_conn === null) {
                startSocket($('#chat').data('websocket-url'));
            }
        } else {
            if (chat_conn !== null) {
                chat_conn.close();
            }
        }
    });
    $('body').on('click', 'a.chat-client-kick', function(e) {
        e.preventDefault();
        var client_id = $(this).data('client-id');
        var message = JSON.stringify({action: 'kick', client_id: client_id});
        chat_conn.send(message);
    });
    $('body').on('click', 'a.chat-client-ban', function(e) {
        e.preventDefault();
        var client_id = $(this).data('client-id');
        var message = JSON.stringify({action: 'ban', client_id: client_id});
        chat_conn.send(message);
    });
});