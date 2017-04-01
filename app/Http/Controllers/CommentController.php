<?php

namespace App\Http\Controllers;

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

    function test() {
        $cm = new Comment();
        $cm->firebase();
        return response()->json($cm);
    }

    function getCommentsByTask($id) {
        $comments = $this->commentService->getCommentsByTask($id);
        return response()->json($comments);
    }

    function comment(Request $request) {
        $comment = $this->commentService->comment($request);
        return response()->json($comment);
    }
}
