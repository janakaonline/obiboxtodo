<?php

namespace App\Http\Controllers;

use App\Repositories\ITaskStatRepository;
use Illuminate\Support\Carbon;

/**
 * Resource controller for the overview stats
 */
class TaskOverviewController extends Controller
{
    protected ITaskStatRepository $taskStatRepo;

    public function __construct(ITaskStatRepository $taskStatRepo)
    {
        $this->taskStatRepo = $taskStatRepo;
    }

    /**
     * Returns completion overview stat
     */
    public function taskCompletionOverview()
    {
        $stats = $this->taskStatRepo->getTotalNumOfTasksUpToDateByCompletedState(Carbon::now());

        return response()->json(['data' => $stats]);
    }

    /**
     * Returns tasks by priority stats
     */
    public function tasksByPriorityOverview()
    {
        $stats = $this->taskStatRepo->getNumOfIncompletedTasksByPriority();

        return response()->json(['data' => $stats]);
    }
}
