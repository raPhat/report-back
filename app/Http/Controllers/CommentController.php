<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentPost;
use App\Models\Comment;
use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{

    public $commentService;
    function __construct(
        CommentService $commentService
    )
    {
        $this->commentService = $commentService;
    }

    public function getCommentsByTask($id) {
        $comments = $this->commentService->getCommentsByTask($id);
        return response()->json($comments);
    }

    function comment(StoreCommentPost $request) {
        $comment = $this->commentService->comment($request);
        return response()->json($comment);
    }

    function destroy($id) {
        $comment = $this->commentService->destroy($id);
        return response()->json($comment);
    }
}
