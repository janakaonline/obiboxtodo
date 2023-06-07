<?php

namespace App\Http\Controllers;

use App\Enums\SortOrder;
use App\Enums\TaskPriority;
use App\Http\Requests\TaskPostRequest;
use App\Http\Requests\TasksGetRequest;
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
    public function index(TasksGetRequest $request)
    {
        $sortPriority = SortOrder::tryFrom($request->sort_priority) ?? null;
        $sortDueDate = SortOrder::tryFrom($request->sort_due_date) ?? null;

        $filterByCompleted = filter_var($request->filter_completed, FILTER_VALIDATE_BOOLEAN);

        $todoItems = $this->taskRepo->getTasks(null, null, $filterByCompleted, $sortDueDate, $sortPriority);

        return response()->json(['items' => $todoItems]);
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(TaskPostRequest $request)
    {
        $priority = TaskPriority::tryFrom($request->priority) ?? TaskPriority::Medium;
        $dueDate = new Carbon($request->due_date);

        $newTask = $this->taskRepo->create($request->name, $priority, $dueDate, $request->description);

        return response()->json(['data' => $newTask], Response::HTTP_OK);
    }

    /**
     * Update the specified task in storage.
     */
    public function update(TaskPostRequest $request, int $id)
    {
        $task = $this->taskRepo->getTaskById($id);
        if (is_null($task)) {
            return response()->json(['error' => 'task not found'], Response::HTTP_NOT_FOUND);
        }

        $priority = TaskPriority::tryFrom($request->priority) ?? TaskPriority::Medium;
        $dueDate = new Carbon($request->due_date);

        if (!$this->taskRepo->update($task, $request->name, $priority, $dueDate, $request->description)) {
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

    /**
     * mark the task as completed
     */
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

    /**
     * mark the task as incomplete
     */
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

    /**
     * Display all tasks which are due today.
     */
    public function tasksDueToday(TasksGetRequest $request)
    {
        $dueDate = new Carbon();
        $sortPriority = SortOrder::tryFrom($request->sort_priority) ?? null;

        $todoItems = $this->taskRepo->getTasksToDoOn($dueDate, $sortPriority);

        return response()->json(['items' => $todoItems]);
    }

    /**
     * Display all the overdue tasks.
     */
    public function overdueTasks(TasksGetRequest $request)
    {
        $dueDate = new Carbon();
        $sortPriority = SortOrder::tryFrom($request->sort_priority) ?? null;

        $todoItems = $this->taskRepo->getOverdueTasksBy($dueDate, $sortPriority);

        return response()->json(['items' => $todoItems]);
    }
}
