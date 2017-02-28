<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public function Type() {
        return $this->belongsTo('App\TaskType', 'task_type_id');
    }
}
