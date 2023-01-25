<?php

namespace App\Http\Controllers\API\Post;

use App\Http\Controllers\Controller;
use App\Http\Resources\Post\Comment\Comment as CommentResource;
use App\Http\Resources\Post\Comment\CommentCollection;
use App\Models\Comment;
use App\Models\Post;

class CommentController extends Controller
{

    public function getComment(Comment $comment): CommentResource
    {
        return CommentResource::make($comment);
    }

    public function getComments(): CommentCollection
    {
        return CommentCollection::make(Comment::all()->paginate());
    }

    public function getAmountOfComments(Post $post): int
    {
        return $post->comments()->where('status', 1)->count();
    }

    public function createComment()
    {

    }

    public function editComment()
    {

    }

    public function deleteComment()
    {

    }
}
