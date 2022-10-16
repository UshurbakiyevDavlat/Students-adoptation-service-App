<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHasHobbies extends Model
{
    use HasFactory;
    protected $table = 'user_has_hobbies';
    protected $guarded = ['id'];
}
