<?php
/**
 * Created by PhpStorm.
 * User: raPhat
 * Date: 3/7/2017 AD
 * Time: 11:31 AM
 */

namespace App\Services;

use App\Models\TaskLog;
use App\Models\User;
use App\Models\Notify;

class UserService
{
    /**
     * @var \App\Models\User
     */
    private $model;
    private $notify;

    private $projectService;
    private $taskService;

    function __construct(
        User $user,
        Notify $notify,
        ProjectService $projectService,
        TaskService $taskService
    )
    {
        $this->model = $user;
        $this->notify = $notify;
        $this->projectService = $projectService;
        $this->taskService = $taskService;
    }

    function getUserById($id) {
        return $this->model->with(['Users', 'Students', 'Mentors', 'Supervisors'])->where('id', $id)->first();
    }

    function getNotifiesByUserId($id) {
        $user = $this->model->with([
            'Notifies',
            'Notifies.Comment',
            'Notifies.Comment.Task',
            'Notifies.Comment.Task.Project',
            'Notifies.Comment.User',
            'Notifies.TaskLog',
            'Notifies.TaskLog.Task',
            'Notifies.TaskLog.Task.Project',
            'Notifies.TaskLog.Task.Project.User',
            'Notifies.TaskLog.TaskType'
        ])->where('id', $id)->first()->toArray();
        $notifies = [];
        foreach ($user['notifies'] as $notify) {
            if( !is_null($notify['comment']) || !is_null($notify['task_log']) ) {
                $notifies[] = $notify;
            }
        }
        return $notifies;
    }

    function getMyStatistic($userId) {
        return $this->getStatisticByUserID($userId);
    }

    function getStatisticByUserID($id) {
        $statistic = [
            'total_projects' => count($this->projectService->getProjectsByUserID($id)),
            'total_tasks' => count($this->taskService->getTasksByUserID($id)),
            'total_days' => 0
        ];
        return $statistic;
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

    function update($data, $id) {
        $user = $this->model->find($id);
        $this->setDetail($user, $data);
        $user->save();

        return $user;
    }

    function getReportsByUserId($id, $dates) {

        $projects = $this->projectService->getMyProjectsByUserID($id);

        foreach ($projects as $project) {
            $reports = [];
            foreach ($dates as $date) {
                $logs = $this->taskService->getTaskLogsByProjectIdAndDates($project['id'], $date->start, $date->end);
                $reports[] = [
                    'logs' => $logs,
                    'start' => $date->start,
                    'end' => $date->end,
                ];
            }
            $project['reports'] = $reports;
        }

        return $projects;
    }

    private function setDetail($user, $data) {
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->description = $data['description'];
        $user->email = $data['email'];
        $user->company = $data['company'];
        $user->position = $data['position'];
        $user->start = $data['start'];
        if( !empty($data['password']) ) {
            $user->password = bcrypt($data['password']);
        }
        if( !empty($data['avatar']) ) {
            $user->avatar = $data['avatar'];
        }
        if( !empty($data['sign']) ) {
            $user->sign = $data['sign'];
        }
    }

    private function hashCode($id) {
        return substr(md5($id), 0, 5);
    }
}