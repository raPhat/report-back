<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public function Task() {
        return $this->belongsTo('App\Models\Task');
    }

    public function User() {
        return $this->belongsTo('App\Models\User');
    }
}
