<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskLog extends Model
{
    public function Task() {
        return $this->belongsTo('App\Task', 'task_id');
    }
}
