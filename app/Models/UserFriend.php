<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserFriend extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'users_friends';
    protected $guarded = ['id'];
    protected $fillable = [
        'user_id',
        'friend_id',
        'status'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function friends(): BelongsTo
    {
        return $this->belongsTo(User::class, 'friend_id', 'id');
    }
}
