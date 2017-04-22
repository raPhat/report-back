<?php
/**
 * Created by PhpStorm.
 * User: raPhat
 * Date: 3/14/2017 AD
 * Time: 11:26 PM
 */

namespace App\Services;


use App\Http\Requests\StoreTaskPost;
use App\Models\Notify;
use App\Models\Task;
use App\Models\TaskLog;
use App\Models\User;

class TaskService
{
    /**
     * @var \App\Models\Task
     */
    private $model;

    /**
     * @var \App\Models\TaskLog
     */
    private $model_log;

    function __construct(
        Task $task,
        TaskLog $log
    )
    {
        $this->model = $task;
        $this->model_log = $log;
    }

    function store(StoreTaskPost $request) {
        $task = new Task();
        $task->name = $request->name;
        $task->description = rawurldecode($request->description);
        $task->start = date('Y-m-d', $request->start['epoc']);
        $task->project_id = $request->project_id;
        $task->save();

        $log = new TaskLog();
        $log->task_id = $task->id;
        $log->start = date('Y-m-d', $request->start['epoc']);
        $log->save();

        return $this->getTaskByTaskID($task->id);
    }

    function update(StoreTaskPost $request, $id) {
        $task = $this->getTaskByTaskID($id);
        $task->name = $request->name;
        $task->description = rawurldecode($request->description);
        $task->save();

        return $task;
    }

    function getTaskLogsByProjectIdAndDates($pid, $start, $end) {
        $logs = TaskLog::with(['Task', 'Task.Comments', 'Task.Comments.User', 'Task.Project', 'Task.Project.User', 'TaskType'])
            ->whereHas('Task.Project', function ($query) use ($pid) {
                $query->where('id', $pid);
            })
            ->whereHas('Task', function ($query) use ($start, $end) {
                $startTime = strtotime($start);
                $endTime = strtotime($end);
                $startDate = date('Y-m-d', $startTime);
                $endDate = date('Y-m-d', $endTime);
                $query->whereBetween('start', [$startDate, $endDate]);
            })->get();
        return $logs;
    }

    function getTaskLogsByUsers($users) {
        $ids = [];
        foreach ($users as $user) {
            $ids[] = $user->id;
        }
        $logs = TaskLog::with(['Task', 'Task.Project', 'Task.Project.User', 'TaskType'])->whereHas('Task.Project.User', function ($query) use ($ids) {
            $query->whereIn('user_id', $ids);
        })->orderBy('created_at', 'desc')->get();

        return $logs;
    }

    function getLogsByUserID($userId) {
        $logs = TaskLog::with(['Task', 'Task.Project', 'Task.Project.User', 'TaskType'])->whereHas('Task.Project', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->orderBy('created_at', 'desc')->get();

        return $logs;
    }

    function getTasksByProjectID($id) {
        $tasks = $this->model->with(['Type', 'Project'])->where('project_id', $id)->get();
        return $tasks;
    }

    function getTaskByTaskID($id) {
        $task = $this->model->with(['Type', 'Project'])->where('id', $id)->first();
        return $task;
    }

    function getTasksByUserID($id) {

        $tasks = $this->model->with(['Type', 'Project'])->whereHas('Project', function ($query) use ($id) {
            $query->where('user_id', $id);
        })->orderBy('created_at', 'desc')->get();

        return $tasks;
    }

    function changeTo($request, $id) {
        $task = $this->getTaskByTaskID($id);

        $log = new TaskLog();
        $log->task_id = $task->id;
        $log->start = date('Y-m-d', $request->start['epoc']);

        switch ($request->type) {
            case 'ToDo':
                $task->task_type_id = 1;
                $task->start = date('Y-m-d', $request->start['epoc']);
                $log->task_type_id = 1;
                $taskId = $task->id;
                Notify::whereHas('TaskLog.Task', function ($query) use ($taskId) {
                    $query->where('id', $taskId);
                })->where('type', 'TASK')->delete();
                $this->model_log->where('task_id', '=', $task->id)->delete();
                break;

            case 'Doing':
                $task->task_type_id = 2;
                $log->task_type_id = 2;
                $taskId = $task->id;
                Notify::whereHas('TaskLog.Task', function ($query) use ($taskId) {
                    $query->where('id', $taskId);
                })->whereHas('TaskLog', function ($query) use ($taskId) {
                    $query->where('task_type_id', '>', 1);
                })->delete();
                $this->model_log->where('task_id', '=', $task->id)->where('task_type_id', '>', 1)->delete();
                break;

            case 'Done':
                $task->task_type_id = 3;
                $log->task_type_id = 3;
                $taskId = $task->id;
                Notify::whereHas('TaskLog.Task', function ($query) use ($taskId) {
                    $query->where('id', $taskId);
                })->whereHas('TaskLog', function ($query) use ($taskId) {
                    $query->where('task_type_id', '>', 2);
                })->delete();
                $this->model_log->where('task_id', '=', $task->id)->where('task_type_id', '>', 2)->delete();
                break;
        }

        $log->save();
        $task->save();

        $notify = new Notify();
        $notify->obj_id = $log->id;
        $notify->type = 'TASK';
        $notify->save();

        $user = $request->user();
        $user = $this->getUserById($user->id)->toArray();

        $mentors = [];
        foreach($user['mentors'] as $mentor) {
            $mentors[] = $mentor['id'];
        }
        $supervisors = [];
        foreach($user['supervisors'] as $supervisor) {
            $supervisors[] = $supervisor['id'];
        }

        $notify->Users()->sync(array_merge($mentors, $supervisors));
        $notify->save();


        $task['notify_ids'] = array_merge($mentors, $supervisors);

        return $task;
    }

    function getUserById($id) {
        return User::with(['Users', 'Students', 'Mentors', 'Supervisors'])->where('id', $id)->first();
    }

}