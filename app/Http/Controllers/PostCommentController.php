<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    PostComment,
    PostCommentReply
};

class PostCommentController extends Controller
{
    public function store(Request $request, $id)
    {
        $postComment = PostComment::create([
            'post_id' => $id,
            'text' => $request->comment,
            'uploaded_by' => $request->uploaded_by
        ]);

        return $postComment;
    }

    public function storeReply(Request $request, $id, $commentId)
    {
        $postCommentReply = PostCommentReply::create([
            'comment_id' => $commentId,
            'text' => $request->comment,
            'uploaded_by' => $request->uploaded_by
        ]);

        return $postCommentReply;
    }
}
