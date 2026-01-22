<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Services\TaskService; // ← これが超重要
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(
        TaskService $service
    ) {
        $this->service = $service;
        $this->middleware('auth');
    }

    private TaskService $service;

    public function index()
    {
        return view('tasks.index');
    }

    public function store(Request $request)
    {
        $task = $this->service->create(
            $request->validate([
                'title' => 'required|string',
                'description' => 'nullable|string',
                'status' => 'in:todo,doing,done',
            ])
        );

        return response()->json($task, 201);
    }

    public function show(Task $task)
    {
        return response()->json($task);
    }

    public function update(Request $request, Task $task)
    {
        return response()->json(
            $this->service->update($task, $request->all())
        );
    }

    public function destroy(Task $task)
    {
        $this->service->delete($task);
        return response()->json(null, 204);
    }

    public function apiIndex()
    {
        return response()->json(
            Task::where('user_id', auth()->id())->get()
        );
    }
}
