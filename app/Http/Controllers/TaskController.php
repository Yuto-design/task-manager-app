<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $tasks = Task::where('user_id', Auth::id())->get();
        return view('tasks.index');
    }

    public function list()
    {
        return response()->json(
            Auth::user()->tasks()->latest()->get()
        );
    }

    public function apiIndex()
    {
        return response()->json(
            Auth::user()->tasks()->orderBy('id', 'desc')->get()
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,doing,done',
        ]);

        $task = Auth::user()->tasks()->create($data);

        return response()->json($task, 201);
    }

    public function update(Request $request, Task $task)
    {
        abort_if($task->user_id !== Auth::id(), 403);

        $task->update($request->only('title', 'description', 'status'));

        return response()->json($task);
    }

    public function destroy(Task $task)
    {
        abort_if($task->user_id !== Auth::id(), 403);

        $task->delete();

        return response()->json(null, 204);
    }
}

