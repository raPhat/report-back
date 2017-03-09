<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskType extends Model
{
    public function Tasks() {
        return $this->hasMany('App\Models\Task');
    }
}
