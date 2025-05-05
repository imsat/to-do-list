<?php

namespace App\Service;

use App\Models\Task;

class TaskService
{
    /**
     * Get all tasks.
     *
     * @param $onlyTrashed
     * @return mixed
     */
    public function taskList($request)
    {
        $limit = $request->get('limit', 10);
        $status = $request->get('status');
        $tasks = Task::where('user_id', auth()->id())
            ->when(!blank($status), fn($q) => $q->where('status', $status))
            ->select(['id', 'title', 'body', 'status', 'created_at'])
            ->latest('id')
            ->cursorPaginate($limit);
        return $tasks;
    }

    /**
     * Create or update task.
     *
     * @param $data
     * @param $task
     * @return Task|null
     */

    public function createOrUpdate($data, $task = null)
    {
        // Check exists task
        if (blank($task)) {
            $task = new Task();
        }
        $task->fill($data);
        $task->save();

        return $task->fresh();
    }

    public function findUserTaskById(int $id)
    {
        return Task::where('user_id', auth()->id())->find($id);
    }

    /**
     * Trash task.
     *
     * @param $task
     * @return true
     */
    public function delete($task)
    {
        // Soft delete a task.
        $task->delete();
        return true;
    }
}
