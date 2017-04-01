<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role', 'description'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function Users()
    {
        return $this->belongsToMany('App\Models\User', 'students_has_users', 'student_id', 'user_id');
    }

    public function Mentors()
    {
        return $this->belongsToMany('App\Models\User', 'students_has_users', 'student_id')->where('role', '=', 'mentor');
    }

    public function Supervisors()
    {
        return $this->belongsToMany('App\Models\User', 'students_has_users', 'student_id')->where('role', '=', 'supervisor');
    }

    public function Students()
    {
        return $this->belongsToMany('App\Models\User', 'students_has_users', 'user_id', 'student_id');
    }
}
