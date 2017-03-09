<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public function Logs() {
        return $this->hasMany('App\Models\TaskLog');
    }

    public function User() {
        return $this->belongsTo('App\Models\User');
    }
}
