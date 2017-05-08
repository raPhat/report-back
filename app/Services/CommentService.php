<?php
/**
 * Created by PhpStorm.
 * User: raPhat
 * Date: 4/1/2017 AD
 * Time: 3:20 PM
 */

namespace App\Services;

use App\Models\Notify;
use App\Models\User;
use Kreait\Firebase\Configuration;
use Kreait\Firebase\Firebase;
use App\Models\Comment;
use App\Models\Task;

class CommentService
{
    /**
     * @var \App\Models\Comment
     */
    private $model;
    private $taskModel;

    function __construct(
        Comment $comment,
        Task $taskModel
    )
    {
        $this->model = $comment;
        $this->taskModel = $taskModel;
    }

    function getComment($id) {
        $comment = $this->model->with(['Task', 'Task.Comments', 'User'])->where('id', $id)->first();
        return $comment;
    }

    function getCommentsByTask($id) {
        $task = $this->taskModel->with(['Comments', 'Comments.User'])->where('id', $id)->first();
        return $task;
    }

    function comment($request, $user) {

        $comment = new Comment();
        $comment->text = rawurldecode($request['text']);
        $comment->user_id = $user->id;
        $comment->task_id = $request['task_id'];

        $comment->save();

//        $this->firebase($comment);
        $task = $this->taskModel->with(['Comments', 'Project'])->where('id', $comment->task_id)->first();
        $comment['task'] = $task;

        $ids = [];
        foreach($task['comments'] as $cm) {
            if($cm['user_id'] != $task['user_id']) {
                $ids[] = $cm['user_id'];
            }
        }
        $ids[] = $task['Project']['user_id'];

        $me = User::with(['Users'])->where('id', $user->id)->first();
        foreach ($me->users as $user) {
            $ids[] = $user['id'];
        }

        $ids = array_unique($ids);

        $notify = new Notify();
        $notify->obj_id = $comment->id;
        $notify->type = 'COMMENT';
        $notify->save();

        $notify->Users()->sync($ids);
        $notify->save();

        $comment['notify_ids'] = $ids;

        return $comment;
    }

    function destroy($id) {
        $comment = $this->model->destroy($id);
        Notify::where('obj_id', $id)->where('type', 'COMMENT')->delete();
        return $comment;
    }

}