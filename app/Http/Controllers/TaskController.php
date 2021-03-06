<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Services\TaskService;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTaskPost;
use App\Models\Task;
use App\Models\TaskLog;
use App\Models\Notify;

class TaskController extends Controller
{
    private $taskService;

    function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

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
     * @param StoreTaskPost|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTaskPost $request)
    {
        $task = $this->taskService->store($request);
        return response()->json($task);
    }

    public function getTasksByProject($id) {
        $tasks = $this->taskService->getTasksByProjectID($id);
        return response()->json($tasks);
    }

    public function getTaskLogsByMe(Request $request) {
        $me = $request->user();
        $user = ($me['role'] == 'student') ? $me['users']: $me['students'];
        $logs = $this->taskService->getTaskLogsByUsers($user);
        return response()->json($logs);
    }

    public function getTaskLogsByMeId(Request $request) {
        $me = $request->user();
        $logs = $this->taskService->getLogsByUserID($me->id);
        return response()->json($logs);
    }

    public function getTaskLogsByUserId($id) {
        $logs = $this->taskService->getLogsByUserID($id);
        return response()->json($logs);
    }

    public function changeTo(Request $request, $id) {
        $task = $this->taskService->changeTo($request, $id);
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
     * @param StoreTaskPost|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreTaskPost $request, $id)
    {
        $task = $this->taskService->update($request, $id);
        return response()->json($task);
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
        Notify::whereHas('TaskLog.Task', function ($query) use ($id) {
            $query->where('id', $id);
        })->where('type', 'TASK')->delete();
        Notify::whereHas('Comment.Task', function ($query) use ($id) {
            $query->where('id', $id);
        })->where('type', 'COMMENT')->delete();
        TaskLog::whereHas('Task', function ($query) use ($id) {
            $query->where('id', $id);
        })->delete();
        Comment::whereHas('Task', function ($query) use ($id) {
            $query->where('id', $id);
        })->delete();
        return response()->json($task);
    }
}
