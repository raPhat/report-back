<?php
/**
 * Created by PhpStorm.
 * User: raPhat
 * Date: 4/1/2017 AD
 * Time: 3:20 PM
 */

namespace App\Services;

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
        $task = $this->taskModel->with(['Comments'])->where('id', $id)->first();
        return $task['comments'];
    }

    function comment($request) {
        $user = $request->user();
        $comment = new Comment();
        $comment->text = $request['text'];
        $comment->user_id = $user->id;
        $comment->task_id = $request['task_id'];

        $comment->save();

        $this->firebase($comment);

        return $comment;
    }

    function firebase($comment) {
        $config = new Configuration();
        $config->setFirebaseSecret('F3IJ8GB83vjopCpZ0o8PHAfanfItNeWkgqEnfPNw');
        $firebase = new Firebase('https://report-ed54c.firebaseio.com', $config);

        $task = $this->taskModel->with(['Comments', 'Project'])->where('id', $comment->task_id)->first();
        foreach($task['comments'] as $cm) {
            if($cm['user_id'] != $task['user_id']) {
                $firebase->set(['comment' => $comment->id], 'user/' . $cm['user_id']);
            }
        }
        $firebase->set(['comment' => $comment->id], 'user/' . $task['Project']['user_id']);
    }

    function destroy($id) {
        $comment = $this->model->destroy($id);
        return $comment;
    }

}