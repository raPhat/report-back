<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('jwt.auth');

Route::post('images', 'GeneralController@upload');

Route::group(['middleware' => 'cors'], function () {

    Route::group(['middleware' => 'jwt.auth'], function () {
        // projects
        Route::get('projects/myProject', 'ProjectController@myProject');
        Route::get('projects/user/{id}', 'ProjectController@getProjectsByUserID');
        Route::get('projects/logs/{id}', 'ProjectController@logs');
        Route::resource('projects', 'ProjectController');
        // tasks
        Route::get('tasks/project/{id}', 'TaskController@getTasksByProject');
        Route::put('tasks/change/{id}', 'TaskController@changeTo');
        Route::get('tasks/logs', 'TaskController@getTaskLogsByMeId');
        Route::get('tasks/logs/me', 'TaskController@getTaskLogsByMe');
        Route::get('tasks/logs/{id}', 'TaskController@getTaskLogsByUserId');
        Route::resource('tasks', 'TaskController');
        // comments
        Route::get('comments/task/{id}', 'CommentController@getCommentsByTask');
        Route::post('comments', 'CommentController@comment');
        Route::delete('comments/{id}', 'CommentController@destroy');
        // users
        Route::get('users/code/{id}', 'UserController@getUserByCode');
        Route::post('users/code', 'UserController@setUserOfStudent');
        Route::get('users/reports', 'UserController@getMyReports');
        Route::get('auth/notifies', 'UserController@getNotifiesByUserId');
        Route::delete('users/code/{id}', 'UserController@deleteUserOfStudent');
        Route::get('users/statistic/{id}', 'UserController@getStatisticByUserID');
        Route::resource('users', 'UserController');
    });
});

Route::post('auth', 'Api\AuthController@authenticate');
Route::get('auth/me', 'Api\AuthController@getAuthenticatedUser');
//Route::post('auth/register', 'Api\AuthController@register');
//
//Route::get('test', 'CommentController@test');
