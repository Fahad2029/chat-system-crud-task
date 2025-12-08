<!DOCTYPE html>
<html>
<head>
<title>SuperAdmin</title>
<script src="https://js.pusher.com/7.2.0/pusher.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.15.0/echo.iife.js"></script>
</head>
<body>
<h2>SuperAdmin Dashboard</h2>
<p>Welcome, {{ auth()->user()->name }}</p>

<h3>Add Task</h3>
<input type="text" id="task_title" placeholder="Title">
<input type="text" id="task_desc" placeholder="Description">
<button onclick="createTask()">Add Task</button>

<h3>Tasks</h3>
<ul id="task_list"></ul>

<h3>Chat</h3>
<input type="text" id="chat_msg" placeholder="Message">
<button onclick="sendMessage()">Send</button>
<div id="chat_box"></div>

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Logout</button>
</form>


<script>
window.Echo = new Echo({
broadcaster:'pusher',
key:'local',
wsHost:'127.0.0.1',
wsPort:6001,
forceTLS:false,
disableStats:true
});

function loadTasks(){
    fetch('/tasks').then(r=>r.json()).then(tasks=>{
        document.getElementById('task_list').innerHTML='';
     tasks.forEach(task=>{
    document.getElementById('task_list').innerHTML += `
        <li data-id="${task.id}" data-title="${task.title}" data-desc="${task.description}">
            ${task.title} (${task.status})
            <button onclick="editTaskFromAttr(this)">Edit</button>
            <button onclick="deleteTask(${task.id})">Delete</button>
        </li>
    `;
});
    });
}

function createTask(){
    fetch('/tasks',{
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body:JSON.stringify({title:document.getElementById('task_title').value,description:document.getElementById('task_desc').value,status:'pending'})
    }).then(()=>loadTasks());
}

function editTaskFromAttr(btn){
    let li = btn.parentElement;
    let id = li.dataset.id;
    let title = li.dataset.title;
    let desc = li.dataset.desc;

    let newTitle = prompt("Enter new title", title);
    if(newTitle === null) return;
    let newDesc = prompt("Enter new description", desc);
    if(newDesc === null) return;

    fetch(`/tasks/${id}`, {
        method:'PUT',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body:JSON.stringify({title:newTitle, description:newDesc})
    }).then(()=> loadTasks());
}

function deleteTask(id){
    fetch(`/tasks/${id}`,{method:'DELETE',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}}).then(()=>loadTasks());
}

loadTasks();

// Listen task updates
window.Echo.channel('tasks').listen('.TaskUpdatedEvent', data=>{
    loadTasks();
    alert(`Task ${data.action}: ${data.task.title}`);
});

// Chat
function sendMessage(){
    let msg = document.getElementById('chat_msg').value;
    fetch('/send-message',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({message:msg,from:'Admin'})});
}

window.Echo.channel('chat-room').listen('.ChatMessageEvent', data=>{
    document.getElementById('chat_box').innerHTML += `<p>${data.from}: ${data.message}</p>`;
});
</script>
</body>
</html>
