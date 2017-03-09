<?php
/**
 * Created by PhpStorm.
 * User: raPhat
 * Date: 3/7/2017 AD
 * Time: 11:31 AM
 */

namespace App\Services;

use App\Models\User;

class UserService
{
    /**
     * @var \App\Models\User
     */
    private $model;

    function __construct(
        User $user
    )
    {
        $this->model = $user;
    }

    function getUserByCode($code) {
        return $this->model->where('code', $code)->first();
    }

    function getMentorsByStudentId($id) {
        $student = $this->model->find($id);
        return $student->Mentors();
    }

    function getSupervisorsByStudentId($id) {
        $student = $this->model->find($id);
        return $student->Supervisors();
    }

    function setUserOfStudent($id, $sid) {
        $student = $this->model->find($sid);
        $student->Users()->sync([$id], false);
        $student->save();
        return $this->withFull($student);
    }

    function deleteUserOfStudent($id, $sid) {
        $student = $this->model->find($sid);
        $student->Users()->detach($id);
        $student->save();
        return $this->withFull($student);
    }

    function create($data) {
        $role = strtolower($data['role']);
        if($role == 'student') {
            return $this->createStudent($data);
        } else if($role == 'supervisor') {
            return $this->createSupervisor($data);
        } else if($role == 'mentor') {
            return $this->createMentor($data);
        }
        return null;
    }

    function createStudent($data) {
        $student = new User();
        $this->setDetail($student, $data);
        $student->role = 'student';

        $student->save();
        return $student;
    }

    function createSupervisor($data) {
        $supervisor = new User();
        $this->setDetail($supervisor, $data);
        $supervisor->role = 'supervisor';
        $supervisor->save();

        $supervisor->code = $this->hashCode($supervisor->id);
        $supervisor->save();
        return $supervisor;
    }

    function createMentor($data) {
        $mentor = new User();
        $this->setDetail($mentor, $data);
        $mentor->role = 'supervisor';
        $mentor->save();

        $mentor->code = $this->hashCode($mentor->id);
        $mentor->save();
        return $mentor;
    }

    function withFull($user) {
        return $user->with(['Users', 'Students', 'Mentors', 'Supervisors']);
    }

    private function setDetail($user, $data) {
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);
    }

    private function hashCode($id) {
        return substr(md5($id), 0, 5);
    }
}