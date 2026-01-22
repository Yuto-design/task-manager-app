<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskService
{
    public function create(array $data): Task
    {
        return Task::create([
            'user_id' => Auth::id(),
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? 'todo',
        ]);
    }

    public function update(Task $task, array $data): Task
    {
        $task->update($data);
        return $task;
    }

    public function delete(Task $task): void
    {
        $task->delete();
    }
}
