<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Messages extends Model
{
    use HasFactory;

    protected $table = 'messages';
    protected $fillable = [
        'chat_id',
        'sender_id',
        'text',
    ];

    public function chat(): BelongsTo
    {
        return $this->belongsTo(PersonalChat::class, 'chat_id');
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'send_user_id');
    }
}
