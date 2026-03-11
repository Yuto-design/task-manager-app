<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Support\Collection;

interface TaskRepositoryInterface
{
    /**
     * @return Collection<int, Task>
     */
    public function getByUserId(int $userId): Collection;

    public function createForUser(int $userId, array $data): Task;

    public function findForUser(int $userId, int $taskId): ?Task;

    public function update(Task $task, array $data): Task;

    public function delete(Task $task): void;
}
