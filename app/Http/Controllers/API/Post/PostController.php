<?php

namespace App\Http\Controllers\API\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostCreateRequest;
use App\Http\Requests\Post\PostDeleteRequest;
use App\Http\Requests\Post\PostUpdateRequest;
use App\Http\Resources\Post\Post as PostResource;
use App\Http\Resources\Post\PostCollection;
use App\Models\Post;
use App\Models\User;

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

    public function getAmountOfLikes(Post $post): int
    {
        return $post->likes()->where('liked', 1)->count();
    }

    public function getSavedPosts(User $user): PostCollection
    {
        return PostCollection::make($user->savedPosts()->paginate());
    }

    public function likePost(Post $post)
    {

    }

    public function addPost(PostCreateRequest $request)
    {

    }

    public function editPost(PostUpdateRequest $request)
    {

    }

    public function deletePost(PostDeleteRequest $request)
    {

    }
}
