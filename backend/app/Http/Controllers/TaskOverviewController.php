<?php

namespace App\Http\Controllers;

use App\Repositories\ITaskStatRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TaskOverviewController extends Controller
{
    protected ITaskStatRepository $taskStatRepo;

    public function __construct(ITaskStatRepository $taskStatRepo)
    {
        $this->taskStatRepo = $taskStatRepo;
    }

    public function taskCompletionOverview()
    {
        $stats = $this->taskStatRepo->getTotalNumOfTasksUpToDateByCompletedState(Carbon::now());

        return response()->json(['data' => $stats]);
    }

    public function tasksByPriorityOverview()
    {
        $stats = $this->taskStatRepo->getNumOfIncompletedTasksByPriority();

        return response()->json(['data' => $stats]);
    }
}
