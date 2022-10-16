<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Hobby extends Model
{
    use HasFactory;

    protected $table = 'hobbies';
    protected $guarded = ['id'];


    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_has_hobbies', 'hobby_id', 'user_id');
    }
}
