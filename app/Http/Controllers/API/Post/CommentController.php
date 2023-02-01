<?php

namespace App\Http\Controllers\API\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\Comment\CommentCreateRequest;
use App\Http\Requests\Post\Comment\CommentUpdateRequest;
use App\Http\Resources\Post\Comment\Comment as CommentResource;
use App\Http\Resources\Post\Comment\CommentCollection;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{

    public function getComment(Comment $comment): CommentResource
    {
        return CommentResource::make($comment);
    }

    public function getComments(): CommentCollection
    {
        return CommentCollection::make(Comment::paginate());
    }

    public function createComment(CommentCreateRequest $request): JsonResponse
    {
        $data = $request->validated();
        $comment = Comment::create($data);
        return response()->json(['message' => 'comment_success_creation', 'data' => $comment], 201);
    }

    public function editComment(Comment $comment, CommentUpdateRequest $request): JsonResponse
    {
        $data = $request->validated();
        $comment->update($data);
        return response()->json(['message' => 'comment_success_editing', 'data' => $comment], 201);
    }

    public function deleteComment(Comment $comment): JsonResponse
    {
        $comment->delete();
        return response()->json(['message' => 'comment_success_deletion']);
    }
}
