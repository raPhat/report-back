<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreProjectPost;
use App\Models\Project;
use App\Models\TaskLog;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::get();
        return response()->json($projects);
    }

    public function logs($id) {
        $logs = $this->getLogs($id);
        return response()->json($logs);
    }

    private function getLogs($id) {
        $project_id = $id;
        $logs = TaskLog::with(['Task', 'Task.Project', 'Task.Project.User', 'TaskType'])->whereHas('Task', function ($query) use ($project_id) {
            $query->where('project_id', $project_id);
        })->orderBy('created_at', 'desc')->get();
        return $logs;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProjectPost $request)
    {
        $user = $request->user();
        $project = new Project();
        $project->name = $request->name;
        $project->description = $request->description;
        $project->start = (isset($request->start['epoc'])) ? date('Y-m-d', $request->start['epoc']) : null;
        $project->user_id = $user->id;
        $project->save();

        return response()->json($project);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = Project::where('id', $id)->first();
        $logs = $this->getLogs($id);
        $project['logs'] = $logs;
        return response()->json($project);
    }

    public function myProject(Request $request) {
        $user = $request->user();
        $projects = Project::where('user_id', $user->id)->get();
//        $logs = $this->getLogs($project->id);
//        $project['logs'] = $logs;
        return response()->json($projects);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreProjectPost $request, $id)
    {
        $project = Project::findOrFail($id);
        $project->name = $request->name;
        $project->description = $request->description;
        $project->start = (isset($request->start['epoc'])) ? date('Y-m-d', $request->start['epoc']) : null;
        $project->save();

        return response()->json($project);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = Project::destroy($id);
        return response()->json($project);
    }
}
