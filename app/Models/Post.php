<?php

namespace App\Models;

use App\Traits\ModelFilterTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;
    use ModelFilterTrait;

    protected $guarded = ['id'];
    protected $fillable = [
        'title',
        'description',
        'body',
        'user_id',
    ];

    protected $with = ['likedUsers','categories'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_post', 'post_id', 'author_id')->without(['hobbies']);
    }

    public function mediaFiles(): HasMany
    {
        return $this->hasMany(MediaFile::class, 'post_id', 'id');
    }


    public function savedPosts(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_saved_posts', 'post_id', 'user_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(PostCategory::class, 'post_has_categories', 'post_id', 'category_id')->withTimestamps();
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_post', 'post_id', 'author_id');
    }


}
