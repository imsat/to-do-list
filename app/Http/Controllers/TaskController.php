<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Service\TaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function __construct(protected TaskService $taskService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tasks = $this->taskService->taskList($request);
        return $this->response(true, 'Task lists!', $tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'body' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->response(false, 'Invalid data!', null, 400, $validator->errors());
        }
        $data = $request->only(['title', 'body', 'status']);
        $data['user_id'] = auth()->id();
        $task = $this->taskService->createOrUpdate($data);
        return $this->response(true, 'Task created successfully.', $task);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $task = $this->taskService->findUserTaskById($id);
        if (blank($task)) {
            return $this->response(false, 'Task not found!', null, 404);
        }
        return $this->response(true, 'Task details!', $task);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $task = $this->taskService->findUserTaskById($id);
        if (blank($task)) {
            return $this->response(false, 'Task not found!', null, 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string',
            'body' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->response(false, 'Invalid data!', null, 400, $validator->errors());
        }
        $data = $request->only(['title', 'body', 'status']);
        $task = $this->taskService->createOrUpdate($data, $task);
        return $this->response(true, 'Task updated successfully.', $task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $task = $this->taskService->findUserTaskById($id);
        if (blank($task)) {
            return $this->response(false, 'Task not found!', null, 404);
        }
        $this->taskService->delete($task);
        return $this->response(true, 'Task deleted successfully.');
    }
}
