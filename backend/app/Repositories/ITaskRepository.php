<?php

namespace App\Repositories;

use App\Enums\SortOrder;
use App\Enums\TaskPriority;
use App\Models\Task;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * describes the interface for the task repository
 */
interface ITaskRepository
{
    /**
     * returns Collection of Tasks with applied filters
     *
     * @return Collection
     */
    public function getTasks(?string $searchTerm = null, ?TaskPriority $priority = null, ?bool $completed = null, ?SortOrder $sortCreatedAt = null): Collection;


    /**
     * returns incomplete tasks on the given date
     *
     * @return Collection collections of tasks retrieved
     */
    public function getTasksToDoOn(Carbon $date, ?SortOrder $sortByPriority = null): Collection;

    /**
     * returns overdue tasks by the given date
     *
     * @return Collection collections of tasks retrieved
     */
    public function getOverdueTasksBy(Carbon $date, ?SortOrder $sortByPriority = null): Collection;

    /**
     * create a new task
     *
     * @return Task created task
     */
    public function create(string $name, TaskPriority $priority = TaskPriority::Medium, string $description = null): Task;

    /**
     * update the existing task
     *
     * @return bool if the update is successful or not
     */
    public function update(Task $task, string $name, TaskPriority $priority, string $description = null): bool;

    /**
     * mark the task complete
     *
     * @return bool if the update is successful or not
     */
    public function markAsComplete(Task $task): bool;

    /**
     * mark the completed task as incomplete
     *
     * @return bool if the update is successful or not
     */
    public function markAsInComplete(Task $task): bool;

    /**
     * remove the task entry
     *
     * @return bool if the deletion is successful or not
     */
    public function delete(Task $task): bool;
}
