<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreTaskPost;
use App\Models\Task;
use App\Models\TaskLog;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTaskPost $request)
    {
        $task = new Task();
        $task->name = $request->name;
        $task->description = $request->description;
        $task->start = date('Y-m-d', $request->start['epoc']);
        $task->project_id = $request->project_id;
        $task->save();

        $log = new TaskLog();
        $log->task_id = $task->id;
        $log->start = date('Y-m-d', $request->start['epoc']);
        $log->save();

        return response()->json($task);
    }

    public function getTasksByProject($id) {
        $tasks = Task::with(['Type'])->where('project_id', $id)->get();
        return response()->json($tasks);
    }

    public function changeTo(Request $request, $id) {
        $task = Task::findOrFail($id);

        $log = new TaskLog();
        $log->task_id = $task->id;
        $log->start = date('Y-m-d', $request->start['epoc']);

        switch ($request->type) {
            case 'ToDo':
                $task->task_type_id = 1;
                $task->start = date('Y-m-d', $request->start['epoc']);
                $log->task_type_id = 1;
                TaskLog::where('task_id', '=', $task->id)->delete();
                break;

            case 'Doing':
                $task->task_type_id = 2;
                $log->task_type_id = 2;
                TaskLog::where('task_id', '=', $task->id)->where('task_type_id', '>', 1)->delete();
                break;

            case 'Done':
                $task->task_type_id = 3;
                $log->task_type_id = 3;
                TaskLog::where('task_id', '=', $task->id)->where('task_type_id', '>', 2)->delete();
                break;
        }

        $log->save();
        $task->save();

        return response()->json($task);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(StoreTaskPost $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::destroy($id);
        return response()->json($task);
    }
}
