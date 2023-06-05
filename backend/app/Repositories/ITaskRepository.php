<?php

namespace App\Repositories;

use App\Enums\SortOrder;
use App\Enums\TaskPriority;
use Illuminate\Database\Eloquent\Collection;

/**
 * describes the interface for the task repository
 */
interface ITaskRepository {
    /**
     * returns Collection of Tasks with applied filters
     *
     * @return Collection
     */
    public function getTasks(?string $searchTerm = null, ?TaskPriority $priority = null, ?bool $completed = null, ?SortOrder $sortCreatedAt = null): Collection;
}
