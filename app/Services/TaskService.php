<?php
/**
 * Created by PhpStorm.
 * User: raPhat
 * Date: 3/14/2017 AD
 * Time: 11:26 PM
 */

namespace App\Services;


use App\Http\Requests\StoreTaskPost;
use App\Models\Task;
use App\Models\TaskLog;

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

        return $task;
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

    function getTasksByUserID($id) {

        $tasks = $this->model->with(['Type', 'Project'])->whereHas('Project', function ($query) use ($id) {
            $query->where('user_id', $id);
        })->orderBy('created_at', 'desc')->get();

        return $tasks;
    }

    function changeTo($request, $id) {
        $task = $this->model->findOrFail($id);

        $log = new TaskLog();
        $log->task_id = $task->id;
        $log->start = date('Y-m-d', $request->start['epoc']);

        switch ($request->type) {
            case 'ToDo':
                $task->task_type_id = 1;
                $task->start = date('Y-m-d', $request->start['epoc']);
                $log->task_type_id = 1;
                $this->model_log->where('task_id', '=', $task->id)->delete();
                break;

            case 'Doing':
                $task->task_type_id = 2;
                $log->task_type_id = 2;
                $this->model_log->where('task_id', '=', $task->id)->where('task_type_id', '>', 1)->delete();
                break;

            case 'Done':
                $task->task_type_id = 3;
                $log->task_type_id = 3;
                $this->model_log->where('task_id', '=', $task->id)->where('task_type_id', '>', 2)->delete();
                break;
        }

        $log->save();
        $task->save();

        return $task;
    }

}