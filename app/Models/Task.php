<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public function Type() {
        return $this->belongsTo('App\Models\TaskType', 'task_type_id');
    }

    public function Project() {
        return $this->belongsTo('App\Models\Project');
    }

    public function Comments() {
        return $this->hasMany('App\Models\Comment');
    }
}
