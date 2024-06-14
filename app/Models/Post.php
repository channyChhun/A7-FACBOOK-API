<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'content',
        'user_id',
        'image_post'
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    // public function comments():HasMany{
    //     return $this->hasMany(Comment::class);
    // }
    // public function likes():HasMany{
    //     return $this->hasMany(Like::class);
    // }
}
