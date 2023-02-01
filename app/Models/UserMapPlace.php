<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserMapPlace extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'users_maps_places';
    protected $guarded = ['id'];
    protected $fillable = [
        'user_id',
        'latitude',
        'longitude',
        'name',
        'address',
        'place_description',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
