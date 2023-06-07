<?php

namespace App\Http\Controllers;

use App\Enums\TaskPriority;
use App\Http\Requests\TaskPostRequest;
use App\Repositories\ITaskRepository;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    protected ITaskRepository $taskRepo;

    public function __construct(ITaskRepository $taskRepo)
    {
        $this->taskRepo = $taskRepo;
    }

    /**
     * Display a listing of the tasks.
     */
    public function index()
    {
        $todoItems = $this->taskRepo->getTasks();

        return response()->json(['items' => $todoItems]);
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(TaskPostRequest $request)
    {
        $validatedData = $request->validated();
        $dueDate = new Carbon($validatedData['due_date']);

        $newTask = $this->taskRepo->create($validatedData->name, $validatedData->priority, $dueDate, $validatedData->description);

        return response()->json(['data' => $newTask], Response::HTTP_OK);
    }

    /**
     * Update the specified task in storage.
     */
    public function update(TaskPostRequest $request, int $id)
    {
        $validatedData = $request->validated();

        $task = $this->taskRepo->getTaskById($id);
        if (is_null($task)) {
            return response()->json(['error' => 'task not found'], Response::HTTP_NOT_FOUND);
        }

        $priority = TaskPriority::tryFrom($validatedData['priority']) ?? TaskPriority::Medium;
        $dueDate = new Carbon($validatedData['due_date']);

        if (!$this->taskRepo->update($task, $validatedData['name'], $priority, $dueDate, $validatedData['description'])) {
            return response()->json(['error' => 'task update failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $task->refresh();

        return response()->json(['data' => $task], Response::HTTP_OK);
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(int $id)
    {
        $task = $this->taskRepo->getTaskById($id);
        if (is_null($task)) {
            return response()->json(['error' => 'task not found'], Response::HTTP_NOT_FOUND);
        }

        if (!$this->taskRepo->delete($task)) {
            return response()->json(['error' => 'task deletion failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $task->refresh();

        return response()->json(['data' => $task], Response::HTTP_OK);
    }

    public function makeAsComplete(int $id)
    {
        $task = $this->taskRepo->getTaskById($id);
        if (is_null($task)) {
            return response()->json(['error' => 'task not found'], Response::HTTP_NOT_FOUND);
        }

        if (!$this->taskRepo->markAsComplete($task)) {
            return response()->json(['error' => 'task mark as complete is failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $task->refresh();

        return response()->json(['data' => $task], Response::HTTP_OK);
    }

    public function makeAsInComplete(int $id)
    {
        $task = $this->taskRepo->getTaskById($id);
        if (is_null($task)) {
            return response()->json(['error' => 'task not found'], Response::HTTP_NOT_FOUND);
        }

        if (!$this->taskRepo->markAsInComplete($task)) {
            return response()->json(['error' => 'task mark as incomplete is failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $task->refresh();

        return response()->json(['data' => $task], Response::HTTP_OK);
    }
}
