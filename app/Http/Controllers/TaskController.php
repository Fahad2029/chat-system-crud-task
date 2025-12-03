<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Events\TaskUpdatedEvent;
use App\Notifications\TaskCreatedNotification;
use App\Models\User;

class TaskController extends Controller
{
    public function index()
    {
        return response()->json(Task::all());
    }

    public function store(Request $request)
    {
        $task = Task::create([
            'title'=>$request->title,
            'description'=>$request->description,
            'status'=>$request->status ?? 'pending'
        ]);

        event(new TaskUpdatedEvent($task,'created'));

        $users = User::where('role','superuser')->get();
        foreach($users as $user){
            $user->notify(new TaskCreatedNotification($task));
        }

        return response()->json($task);
    }

    public function update(Request $request, Task $task)
    {
        $task->update($request->all());
        event(new TaskUpdatedEvent($task,'updated'));
        return response()->json($task);
    }

    public function destroy(Task $task)
    {
        event(new TaskUpdatedEvent($task,'deleted'));
        $task->delete();
        return response()->json(['status'=>'deleted']);
    }
}
