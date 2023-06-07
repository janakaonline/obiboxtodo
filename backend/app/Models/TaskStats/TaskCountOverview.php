<?php

namespace App\Models\TaskStats;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Collection;
use JsonSerializable;

class TaskCountOverview implements Jsonable, Arrayable, JsonSerializable
{
    protected Collection $taskCountList;

    public function __construct()
    {
        $this->taskCountList = collect();
    }

    public function addCount(TaskCount $taskCount)
    {
        $this->taskCountList->push($taskCount);
    }

    public function getCountList(): Collection
    {
        return $this->taskCountList;
    }

    public function getCountOf(string $key): int
    {
        $item = $this->taskCountList->firstWhere('key', $key);
        return $item ? $item->count : 0;
    }

    public function jsonSerialize(): mixed{
        return $this->toArray();
    }

    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    public function toArray()
    {
        $outputArr = [];
        $this->taskCountList->each(function (TaskCount $item) use (&$outputArr) {
            $outputArr[$item->key] = $item->count;
        });
        return $outputArr;
    }
}
