<?php

namespace App\Repositories;

use App\Enums\SortOrder;
use App\Enums\TaskPriority;
use App\Models\Task;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

/**
 * Defines functionlity of the task repository
 * Responsible of manage underlying DB logic related to Task model
 */
class TaskRepository implements ITaskRepository
{

    /**
     * returns tasks with applied filters
     *
     * @return Collection collections of tasks retrieved
     */
    public function getTasks(?string $searchTerm = null, ?TaskPriority $priority = null, ?bool $completed = null, ?SortOrder $sortCreatedAt = null): Collection
    {

        $model = Task::query();

        if (!empty($searchTerm)) {
            $model->where(function (Builder $q) use ($searchTerm) {
                $q->where('name', 'LIKE', "$searchTerm%")
                    ->orWhere('description', 'LIKE', "%$searchTerm%");
            });
        }

        if (!empty($priority)) {
            $model->where('priority', $priority->value);
        }

        if (!is_null($completed)) {
            $model->where('completed', $completed);
        }

        if (!empty($sortCreatedAt)) {
            $model->orderBy('created_at', $sortCreatedAt->value);
        }

        return $model->get();
    }

    /**
     * returns incomplete tasks on the given date
     *
     * @return Collection collections of tasks retrieved
     */
    public function getTasksToDoOn(Carbon $date, ?SortOrder $sortByPriority = null): Collection
    {
        $model = Task::where('completed', false);

        if (!is_null($date)) {
            $model->where('due_date', '>=', $date->copy()->startOfDay())
                ->where('due_date', '<=', $date->copy()->endOfDay());
        }

        if ($sortByPriority) {
            $priorityOrderString = "'" . TaskPriority::Low->value . "', '" . TaskPriority::Medium->value . "', '" . TaskPriority::High->value . "'";
            $model->orderByRaw("FIELD(priority, $priorityOrderString) {$sortByPriority->value}");
        }


        return $model->get();
    }

    /**
     * returns overdue tasks by the given date
     *
     * @return Collection collections of tasks retrieved
     */
    public function getOverdueTasksBy(Carbon $date, ?SortOrder $sortByPriority = null): Collection
    {
        $model = Task::where('completed', false);

        if (!is_null($date)) {
            $model->where('due_date', '<', $date->copy()->startOfDay());
        }

        if ($sortByPriority) {
            $priorityOrderString = "'" . TaskPriority::Low->value . "', '" . TaskPriority::Medium->value . "', '" . TaskPriority::High->value . "'";
            $model->orderByRaw("FIELD(priority, $priorityOrderString) {$sortByPriority->value}");
        }


        return $model->get();
    }

    /**
     * create a new task
     *
     * @return Task created task
     */
    public function create(string $name, TaskPriority $priority = TaskPriority::Medium, string $description = null): Task
    {
        return Task::create([
            'name' => $name,
            'priority' => $priority->value,
            'description' => $description
        ]);
    }

    /**
     * update the existing task
     *
     * @return bool if the update is successful or not
     */
    public function update(Task $task, string $name, TaskPriority $priority, string $description = null): bool
    {
        $task->fill([
            'name' => $name,
            'priority' => $priority->value,
            'description' => $description
        ]);

        return $task->save();
    }

    /**
     * mark the task complete
     *
     * @return bool if the update is successful or not
     */
    public function markAsComplete(Task $task): bool
    {
        // if the task is already completed, skip the update
        if ($task->completed === true) {
            return true;
        }

        $task->completed = true;
        $task->completed_at = Carbon::now();
        return $task->save();
    }

    /**
     * mark the completed task as incomplete
     *
     * @return bool if the update is successful or not
     */
    public function markAsInComplete(Task $task): bool
    {
        // if the task is already incomplete, skip the update
        if ($task->completed === false) {
            return true;
        }

        $task->completed = false;
        $task->completed_at = null;
        return $task->save();
    }

    /**
     * remove the task entry
     *
     * @return bool if the deletion is successful or not
     */
    public function delete(Task $task): bool
    {
        return $task->delete();
    }
}
