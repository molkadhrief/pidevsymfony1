var conn;
var conn2;
function openWeb() {
    console.log("Connecting to WebSocket...");
    conn = new WebSocket('ws://localhost:8080');
    conn2 = new WebSocket('ws://localhost:8080');

    conn.onopen = function (e) {
        console.log("Connection established!");
    };

    conn.onmessage = function (e) {
        console.log("Received message:", e.data);
        document.getElementById('output').innerHTML += '<p>' + e.data + '</p>';
    };

    conn.onclose = function (e) {
        console.log("Connection closed.");
    };
}

function joinRoom() {
    var room = document.getElementById('room').value;
    if (conn && conn.readyState === WebSocket.OPEN) {
        var message = JSON.stringify({
            action: 'join',
            room: room
        });
        conn.send(message);
        console.log("Joined room:", room);
    } else {
        console.log("WebSocket connection not open.");
    }
}

function sendMessage() {
    var messageInput = document.getElementById('message');
    var room = document.getElementById('room').value;
    var message = messageInput.value;
    if (conn && conn.readyState === WebSocket.OPEN && message.trim() !== '') {
        var messageObj = {
            action: 'message',
            message: message,
            room: room
        };
        conn.send(JSON.stringify(messageObj));
        console.log("Sent message to room:", room);
        document.getElementById('output').innerHTML += '<p class="ownMessage">' + message + '</p>';
        messageInput.value = '';
    } else {
        console.log("WebSocket connection not open or message is empty.");
    }
}
window.onload = function(){
    openWeb();
}