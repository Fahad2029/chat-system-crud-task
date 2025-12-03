<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <script src="https://js.pusher.com/7.2.0/pusher.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.15.0/echo.iife.js"></script>
</head>
<body>

<h2>Admin Panel</h2>
<div id="messages" style="border:1px solid #ccc; padding:10px; width:300px; height:300px; overflow:auto;"></div>

<input type="text" id="msg" placeholder="Type message">
<button onclick="sendMessage()">Send</button>

<script>
Pusher.logToConsole = true;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'local',
    wsHost: '127.0.0.1',
    wsPort: 6001,
    forceTLS: false,
    disableStats: true,
});

// Listen to chat-room channel
window.Echo.channel('chat-room')
    .listen('.ChatMessageEvent', function(e){
        console.log('Message received:', e);
        document.getElementById('messages').innerHTML += `<p><strong>${e.from}:</strong> ${e.message}</p>`;
    });

// Send message as Admin
function sendMessage() {
    const message = document.getElementById('msg').value;

    fetch('/send-message', {
        method: 'POST',
        headers: {
            'Content-Type':'application/json',
            'Accept':'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ message, from: 'Admin' })
    })
    .then(res => res.json())
    .then(data => {
        console.log('Sent:', data);
        document.getElementById('msg').value = '';
    });
}
</script>

</body>
</html>
