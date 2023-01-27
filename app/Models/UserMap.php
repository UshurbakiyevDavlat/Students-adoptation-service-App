<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserMap extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'users_maps';
    protected $guarded = ['id'];
    protected $fillable = [
        'user_id',
        'latitude',
        'longitude',
        'name',
        'address',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
