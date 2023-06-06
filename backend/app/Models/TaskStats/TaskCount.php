<?php

namespace App\Models\TaskStats;

use Illuminate\Contracts\Support\Jsonable;

class TaskCount implements Jsonable {
    public string $key;
    public int $count;

    public function __construct(string $key, int $count)
    {
        $this->key = $key;
        $this->count = $count;
    }

    public function toJson($options = 0){
        return json_encode([$this->key => $this->count], $options);
    }
}
