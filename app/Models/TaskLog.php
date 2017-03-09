<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskLog extends Model
{
    public function Task() {
        return $this->belongsTo('App\Models\Task', 'task_id');
    }
}
