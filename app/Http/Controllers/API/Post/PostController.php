<?php

namespace App\Http\Controllers\API\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostCreateRequest;
use App\Http\Requests\Post\PostUpdateRequest;
use App\Http\Resources\Post\Post as PostResource;
use App\Http\Resources\Post\PostCollection;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{

    public function getPosts(): PostCollection
    {
        return PostCollection::make((new Post())->setFilters(['description', 'title'])->getFiltered());
    }

    public function getPost(Post $post): PostResource
    {
        return PostResource::make($post);
    }

    public function getSavedPosts(): PostCollection
    {
        return PostCollection::make(auth()->user()->savedPosts()->paginate());
    }

    public function likePost(Post $post): JsonResponse
    {
        $post->likes()
            ->where('author_id', auth()->user()->id)
            ->withPivot('liked')
            ->first()
            ->pivot->update(['liked' => 1]);

        return response()->json(['message' => 'Post liked']);
    }

    public function savePost(Post $post): JsonResponse
    {
        $post->savedPosts()->attach(auth()->user()->id);
        return response()->json(['message' => 'Post saved']);
    }

    public function addPost(PostCreateRequest $request): JsonResponse
    {
        $data = $request->validated();
        $post = Post::create($data);
        $user = auth()->user();
        $user->posts()->save($post);

        return response()->json(['message' => 'Post created', 'post' => $post]);
    }

    public function editPost(Post $post, PostUpdateRequest $request): JsonResponse
    {
        $data = $request->validated();
        $post->update($data);

        return response()->json(['message' => 'Post updated', 'post' => $post]);
    }

    public function deletePost(Post $post): JsonResponse
    {
        $post->delete();
        return response()->json(['message' => 'Post deleted successfully']);
    }
}
