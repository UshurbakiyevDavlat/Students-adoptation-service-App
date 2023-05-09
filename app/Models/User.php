<?php

namespace App\Models;

use App\Traits\ModelFilterTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Helpers\Helpers;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens;
    use HasFactory;
    use HasRoles;
    use Notifiable;
    use ModelFilterTrait;

    protected $with = ['hobbies'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'phone',
        'name',
        'email',
        'uuid',
        'birth_date',
        'city_id',
        'university_id',
        'speciality_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'birth_date' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function hobbies(): BelongsToMany
    {
        return $this->belongsToMany(Hobby::class, 'user_has_hobbies', 'user_id', 'hobby_id')->withTimestamps();
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'author_id', 'id');
    }

    public function savedPosts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'users_saved_posts', 'user_id', 'post_id')->withTimestamps();
    }

    public function mapsLocation(): HasMany
    {
        return $this->hasMany(UserMap::class, 'user_id', 'id');
    }

    public function mapsFriendsLocation(): HasManyThrough
    {
        return $this->hasManyThrough(UserMap::class, UserFriend::class, 'user_id', 'user_id', 'id', 'friend_id')
            ->select('users_maps.*', 'users_friends.friend_id as friend_user_id');
    }

    public function mapPlaces(): HasMany
    {
        return $this->hasMany(UserMapPlace::class, 'user_id', 'id');
    }

    public function mapsPlaces($model,$long,$lat,$range,$friend_ids = []): array
    {
        return Helpers::getNearbyLocations($model,$lat,$long,$range,$friend_ids);
    }

    public function friends(): HasMany
    {
        return $this->hasMany(UserFriend::class, 'user_id', 'id');
    }

    public function friendsRequests(): HasMany
    {
        return $this->hasMany(UserFriendRequest::class, 'friend_id', 'id');
    }

    public function userFriendRequests(): HasMany
    {
        return $this->hasMany(UserFriendRequest::class, 'user_id', 'id');
    }

    public function userChats(): BelongsToMany
    {
        return $this->belongsToMany(__CLASS__, 'personal_chats', 'first_participant', 'second_participant')
            ->withTimestamps()
            ->without('hobbies')
            ->withPivot('id');
    }

}
