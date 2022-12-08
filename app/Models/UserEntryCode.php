<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEntryCode extends Model
{
    use HasFactory;
    protected $table = 'user_entries_code';
    protected $guarded = ['id'];
}
