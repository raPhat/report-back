<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userService;

    function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    function show($id) {
        return $this->userService->getUserById($id);
    }

    function update(Request $request, $id) {
        return $this->userService->update($request, $id);
    }

    function getUserByCode($code) {
        $user = $this->userService->getUserByCode($code);
        return response()->json($user);
    }

    function getStatisticByUserID($id) {
        $statistic = $this->userService->getStatisticByUserID($id);
        return response()->json($statistic);
    }

    function setUserOfStudent(Request $request) {
        $me = $request->user();
        $user = $request->user;
        $response = $this->userService->setUserOfStudent($user['id'], $me->id);
        return response()->json($response->first());
    }

    function deleteUserOfStudent(Request $request, $id) {
        $me = $request->user();
        $user = $this->userService->deleteUserOfStudent($id, $me->id);
        return response()->json($user->first());
    }
}
