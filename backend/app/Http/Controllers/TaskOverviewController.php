<?php

namespace App\Http\Controllers;

use App\Repositories\ITaskStatRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
}
