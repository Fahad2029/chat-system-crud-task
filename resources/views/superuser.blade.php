<!DOCTYPE html>
<html>
<head>
<title>SuperUser</title>
<script src="https://js.pusher.com/7.2.0/pusher.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.15.0/echo.iife.js"></script>
</head>
<body>
<h2>SuperUser Dashboard</h2>
<p>Welcome, {{ auth()->user()->name }}</p>

<h3>Tasks</h3>
<ul id="task_list"></ul>

<h3>Chat</h3>
<div id="chat_box"></div>

<h3>Send Message</h3>
<input type="text" id="chat_msg_user" placeholder="Message">
<button onclick="sendMessageUser()">Send</button>

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Logout</button>
</form>


<script>
// Echo init
window.Echo = new Echo({
broadcaster:'pusher',
key:'local',
wsHost:'127.0.0.1',
wsPort:6001,
forceTLS:false,
disableStats:true
});

// Load tasks
function loadTasks(){
    fetch('/tasks').then(r=>r.json()).then(tasks=>{
        document.getElementById('task_list').innerHTML='';
        tasks.forEach(t=>{
            document.getElementById('task_list').innerHTML += `<li>${t.title} (${t.status})</li>`;
        });
    });
}

loadTasks();

// Task real-time
window.Echo.channel('tasks').listen('.TaskUpdatedEvent', data=>{
    loadTasks();
    new Notification(`Task ${data.action}`, {body:data.task.title});
});

// Request browser notification
if(Notification.permission!=='granted'){ Notification.requestPermission(); }

function sendMessageUser(){
    let msg = document.getElementById('chat_msg_user').value;
    fetch('/send-message', {
        method: 'POST',
        headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN':'{{ csrf_token() }}'
        },
        body: JSON.stringify({message: msg, from: 'User'})
    });
    document.getElementById('chat_msg_user').value = '';
}

// Chat
window.Echo.channel('chat-room').listen('.ChatMessageEvent', data=>{
    document.getElementById('chat_box').innerHTML += `<p>${data.from}: ${data.message}</p>`;
});


</script>
</body>
</html>
