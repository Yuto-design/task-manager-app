<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Support\Collection;

class EloquentTaskRepository implements TaskRepositoryInterface
{
    public function getByUserId(int $userId): Collection
    {
        return Task::where('user_id', $userId)->orderBy('id', 'desc')->get();
    }

    public function createForUser(int $userId, array $data): Task
    {
        $data['user_id'] = $userId;

        return Task::create($data);
    }

    public function findForUser(int $userId, int $taskId): ?Task
    {
        return Task::where('user_id', $userId)->where('id', $taskId)->first();
    }

    public function update(Task $task, array $data): Task
    {
        $task->update($data);

        return $task->fresh();
    }

    public function delete(Task $task): void
    {
        $task->delete();
    }
}
