<?php

namespace App\Http\Controllers\API\Post;

use App\Http\Controllers\Controller;
use App\Http\Resources\Post\PostCollection;
use App\Http\Resources\Post\Post as PostResource;
use App\Models\Post;

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

    public function getComments()
    {

    }

    public function getCategories()
    {

    }

    public function getAmountOfComments()
    {

    }

    public function likePost()
    {

    }

    public function getAmountOfLikes()
    {

    }

    public function getSavedPosts()
    {

    }

    public function addPost()
    {

    }

    public function editPost()
    {

    }

    public function deletePost()
    {

    }
}
