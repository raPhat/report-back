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

    function getMyReports(Request $request) {
        $user = $request->user();
        $dates = json_decode($request['dates']);
        $reports = $this->userService->getReportsByUserId($user->id, $dates);
        return response()->json($reports);
    }

    function getUserByCode($code) {
        $user = $this->userService->getUserByCode($code);
        return response()->json($user);
    }

    function getStatisticByUserID($id) {
        $statistic = $this->userService->getStatisticByUserID($id);
        return response()->json($statistic);
    }

    function getStatistic(Request $request) {
        $me = $request->user();
        $users = ($me['role'] == 'student') ? $me['users']: $me['students'];
        $statistic = $this->userService->getStatisticByUsers($users);
        return response()->json($statistic);
    }

    function setUserOfStudent(Request $request) {
        $me = $request->user();
        $user = $request->user;
        $response = $this->userService->setUserOfStudent($user['id'], $me->id);
        return response()->json($response->first());
    }

    function getNotifiesByUserId(Request $request) {
        $me = $request->user();
        $notifies = $this->userService->getNotifiesByUserId($me->id);
        return response()->json($notifies);
    }

    function deleteUserOfStudent(Request $request, $id) {
        $me = $request->user();
        $user = $this->userService->deleteUserOfStudent($id, $me->id);
        return response()->json($user->first());
    }
}
