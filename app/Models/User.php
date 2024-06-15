<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_img',
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
        'password' => 'hashed',
    ];
    public function posts():HasMany{
        return $this->hasMany(Post::class);
    }
    // public function comments():HasMany{
    //     return $this->hasMany(Comment::class);
    // }
    public function likes():HasMany{
        return $this->hasMany(Like::class);
    }

    // it is for Friend(Ex: addfried,acceptfriend.....)
    public function friends()
    {
        return $this->hasMany(Friend::class, 'user_id');
    }

    public function friendRequests()
    {
        return $this->hasMany(Friend::class, 'user_id')->where('status', 'pending');
    }

    public function friendsPending()
    {
        return $this->hasMany(Friend::class, 'user_id')->where('status', 'pending');
    }

    public function friendsAccepted()
    {
        return $this->hasMany(Friend::class, 'user_id')->where('status', 'accepted');
    }

    public function friendsRejected()
    {
        return $this->hasMany(Friend::class, 'user_id')->where('status', 'rejected');
    }

    public function friendsBlocked()
    {
        return $this->hasMany(Friend::class, 'user_id')->where('status', 'blocked');
    }

    public function friendsBlockedBy()
    {
        return $this->hasMany(Friend::class, 'friend_id')->where('status', 'blocked');
    }

    public function friendsPendingFrom()
    {
        return $this->hasMany(Friend::class, 'friend_id')->where('status', 'pending');
    }

    public function friendsAcceptedFrom()
    {
        return $this->hasMany(Friend::class, 'friend_id')->where('status', 'accepted');
    }


}

