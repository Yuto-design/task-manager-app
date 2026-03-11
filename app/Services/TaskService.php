<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use App\Repositories\TaskRepositoryInterface;
use Illuminate\Support\Collection;

class TaskService
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository
    ) {
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTasksForUser(User $user): Collection
    {
        return $this->taskRepository->getByUserId((int) $user->id);
    }

    public function createTask(User $user, array $data): Task
    {
        return $this->taskRepository->createForUser((int) $user->id, $data);
    }

    public function updateTask(User $user, int $taskId, array $data): Task
    {
        $task = $this->taskRepository->findForUser((int) $user->id, $taskId);

        if ($task === null) {
            abort(403);
        }

        return $this->taskRepository->update($task, $data);
    }

    public function deleteTask(User $user, int $taskId): void
    {
        $task = $this->taskRepository->findForUser((int) $user->id, $taskId);

        if ($task === null) {
            abort(403);
        }

        $this->taskRepository->delete($task);
    }
}
