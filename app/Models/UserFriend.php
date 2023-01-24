<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserFriend extends Model
{
    use HasFactory;

    protected $table = ['users_friends'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function friends(): BelongsTo
    {
        return $this->belongsTo(User::class, 'friend_id', 'id');
    }
}
