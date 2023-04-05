<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PersonalChat extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'personal_chats';
    protected $fillable = [
        'first_participant',
        'second_participant',
    ];

    public function firstUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'first_participant');
    }

    public function secondUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'second_participant');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Messages::class, 'chat_id');
    }
}
