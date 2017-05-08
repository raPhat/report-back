<?php
/**
 * Created by PhpStorm.
 * User: raPhat
 * Date: 3/14/2017 AD
 * Time: 10:44 PM
 */

namespace App\Services;


use App\Models\Project;
use App\Models\TaskLog;

class ProjectService
{
    /**
     * @var \App\Models\Project
     */
    private $model;
    private $modelTaskLog;

    function __construct(
        Project $project,
        TaskLog $taskLog
    )
    {
        $this->model = $project;
        $this->modelTaskLog = $taskLog;
    }

    function all() {
        $projects = $this->model->get();
        return $projects;
    }

    function show($id) {
        $project = $this->model->where('id', $id)->first();
        if(!is_null($project)) {
            $logs = $this->getLogsByProject($id);
            $project['logs'] = $logs;
        }

        return $project;
    }

    function store($request, $userId) {

        if(!isset($request->name) || !isset($request->description)) {
            return false;
        }

        $project = new Project();
        $project->name = $request->name;
        $project->description = $request->description;
        $project->start = (isset($request->start['epoc'])) ? date('Y-m-d', $request->start['epoc']) : null;
        $project->user_id = $userId;
        $project->save();

        return $project;
    }

    function update($request, $id) {

        if(!isset($request->name) || !isset($request->description)) {
            return false;
        }

        $project = $this->model->findOrFail($id);
        $project->name = $request->name;
        $project->description = $request->description;
        $project->start = (isset($request->start['epoc'])) ? date('Y-m-d', $request->start['epoc']) : null;
        $project->save();

        return $project;
    }

    function destroy($id) {
        if(!is_integer($id)) {
            return false;
        }
        $project = $this->model->destroy($id);

        return $project;
    }

    function getMyProjectsByUserID($id) {
        if(!is_integer($id)) {
            return false;
        }
        return $this->getProjectsByUserID($id);
    }

    function getProjectsByUserID($id) {
        if(!is_integer($id)) {
            return false;
        }
        return $this->model->with(['Tasks', 'Tasks.Type'])->where('user_id', $id)->get();
    }

    function getLogsByProject($id) {
        $project_id = $id;
        $logs = $this->modelTaskLog->with(['Task', 'Task.Project', 'Task.Project.User', 'TaskType'])->whereHas('Task', function ($query) use ($project_id) {
            $query->where('project_id', $project_id);
        })->orderBy('created_at', 'desc')->get();

        return $logs;
    }

}