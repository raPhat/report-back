<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notify extends Model
{
    public function Users() {
        return $this->belongsToMany('App\Models\User', 'users_has_notifies', 'notify_id', 'user_id');
    }

    public function Comment() {
        return $this->belongsTo('App\Models\Comment', 'obj_id');
    }

    public function TaskLog() {
        return $this->belongsTo('App\Models\TaskLog', 'obj_id');
    }
}
