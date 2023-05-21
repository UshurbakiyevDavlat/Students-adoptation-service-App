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

    public function getPosts($category): JsonResponse|PostCollection
    {
        if ($category === 'all') {
            return PostCollection::make(Post::paginate());
        }

        if (!is_int($category)) {
            return response()->json(['message' => 'Invalid category'], 400);
        }

        return PostCollection::make(Post::whereHas('categories', static function ($query) use ($category) {
            $query->where('category_id', $category);
        })->paginate());
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
        $alreadyLiked = $post->likes()
            ->where('author_id', auth()->user()->id)
            ->withPivot('liked')
            ->first()
            ?->pivot
            ?->liked;

        if ($alreadyLiked) {
            $post->likes()->detach(auth()->user()->id);
        } else {
            $post->likes()->attach(auth()->user()->id, ['liked' => 1, 'created_at' => now(), 'updated_at' => now()]);
        }

        return response()->json(['message' => !$alreadyLiked ? 'Post liked' : 'Post unliked', 'isLiked' => !$alreadyLiked]);
    }

    public function savePost(Post $post): JsonResponse
    {
        $alreadySaved = $post->savedPosts()
            ->where('user_ID', auth()->user()->id)
            ->first();

        if ($alreadySaved) {
            $post->savedPosts()->detach(auth()->user()->id);
        } else {
            $post->savedPosts()->attach(auth()->user()->id, ['created_at' => now(), 'updated_at' => now()]);
        }

        return response()->json(['message' => !$alreadySaved ? 'Post saved' : 'Post unsaved']);
    }

    public function addPost(PostCreateRequest $request): JsonResponse
    {
        $data = $request->validated();
        $postCategories = $data['categories_ids'];
        unset($data['categories_ids']);

        $data['user_id'] = auth()->user()->id;
        $post = Post::create($data);
        $post->categories()->attach($postCategories);

        return response()->json(['message' => 'Post created', 'post' => $post->with('categories')->find($post->id)]);
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
