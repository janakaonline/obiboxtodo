<?php

namespace App\Repositories;

use App\Models\Task;
use App\Models\TaskStats\TaskCountOverview;
use App\Models\TaskStats\TaskCount;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Defines functionlity of the task repository
 * Responsible of providing related statistic or overview data of the Task model
 */
class TaskStatRepository implements ITaskStatRepository
{
    /**
     * returns task completion overview within the given date range
     *
     * @return Collection collections of tasks retrieved
     */
    public function getTotalNumOfTasksUpToDateByCompletedState(Carbon $date): TaskCountOverview
    {
        $countStat = Task::where('due_date', '<=', $date)
            ->select('completed', DB::raw('count(id) as total'))
            ->groupBy('completed')->get();


        $completedCount = $countStat->firstWhere('completed', 1);
        $todoCount = $countStat->firstWhere('completed', 0);
        $totalCount = $countStat->sum('total');


        $taskOverview = new TaskCountOverview();
        $taskOverview->addCount(new TaskCount('completed', $completedCount ? $completedCount->total : 0));
        $taskOverview->addCount(new TaskCount('todo', $todoCount ? $todoCount->total : 0));
        $taskOverview->addCount(new TaskCount('total', $totalCount));

        return $taskOverview;
    }
}
