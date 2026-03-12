<?php

namespace App\Http\Controllers;

use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function __construct(
        private TaskService $taskService
    ) {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('tasks.index');
    }

    public function apiIndex()
    {
        $tasks = $this->taskService->getTasksForUser(Auth::user());

        return response()->json($tasks->all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,doing,done',
        ]);

        $task = $this->taskService->createTask(Auth::user(), $data);

        return response()->json($task, 201);
    }

    public function update(Request $request, int $id)
    {
        $data = $request->only('title', 'description', 'status');
        $updated = $this->taskService->updateTask(Auth::user(), $id, $data);

        return response()->json($updated);
    }

    public function destroy(int $id)
    {
        $this->taskService->deleteTask(Auth::user(), $id);

        return response()->json(null, 204);
    }
}
