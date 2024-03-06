function openWeb() {
    console.log("Connecting to WebSocket...");
    var Bigconn = new WebSocket('ws://localhost:8080');

    Bigconn.onopen = function (e) {
        console.log("Big Connection established!!!");
    };

    Bigconn.onmessage = function (e) {
        console.log("Received message:", e.data);
        document.getElementById('output').innerHTML += '<p>' + e.data + '</p>';
    };

    Bigconn.onclose = function (e) {
        console.log("Connection closed.");
    };
}
window.onload = function(){
    openWeb();
}
