<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        return view('tasks.index');
    }

    public function apiIndex()
    {
        return response()->json(Task::all());
    }

    public function store(Request $request)
    {
        $task = Task::create(
            $request->validate([
                'title' => 'required|string',
                'description' => 'nullable|string',
                'status' => 'required|in:todo,doing,done',
            ])
        );

        return response()->json($task, 201);
    }

    public function update(Request $request, Task $task)
    {
        $task->update($request->all());
        return response()->json($task);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(null, 204);
    }
}
