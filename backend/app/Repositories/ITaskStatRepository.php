<?php

namespace App\Repositories;

use App\Models\TaskStats\TaskCountOverview;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

/**
 * describes the interface for the task stat repository
 */
interface ITaskStatRepository
{
    /**
     * returns task completion overview within the given date range
     *
     * @return Collection collections of tasks retrieved
     */
    public function getTotalNumOfTasksUpToDateByCompletedState(Carbon $date): TaskCountOverview;

    /**
     * returns number of incompleted tasks grouped by the priority 
     *
     * @return Collection collections of tasks retrieved
     */
    public function getNumOfIncompletedTasksByPriority(): TaskCountOverview;
}
