<?php

namespace App\Http\Controllers;

use App\Services\ProjectService;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProjectPost;

class ProjectController extends Controller
{
    private $projectService;

    function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = $this->projectService->all();
        return response()->json($projects);
    }

    public function logs($id) {
        $logs = $this->projectService->getLogsByProject($id);
        return response()->json($logs);
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
     * @param StoreProjectPost|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProjectPost $request)
    {
        $user = $request->user();
        $project = $this->projectService->store($request, $user->id);
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
        $project = $this->projectService->show($id);
        return response()->json($project);
    }

    public function myProject(Request $request) {
        $user = $request->user();
        $projects = $this->projectService->getMyProjectsByUserID($user->id);
        return response()->json($projects);
    }

    public function getProjectsByUserID($id) {
        $projects = $this->projectService->getMyProjectsByUserID($id);
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
     * @param StoreProjectPost|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreProjectPost $request, $id)
    {
        $project = $this->projectService->update($request, $id);
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
        $project = $this->projectService->destroy($id);
        return response()->json($project);
    }
}
