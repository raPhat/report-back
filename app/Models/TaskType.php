<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskType extends Model
{
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function Tasks() {
        return $this->hasMany('App\Models\Task');
    }
}
