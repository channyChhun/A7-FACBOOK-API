<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
=======
>>>>>>> 7a6e651e9e5eb4d1531295815a266653383c4edb

class Friend extends Model
{
    use HasFactory;
    protected $fillable = [
<<<<<<< HEAD
        'name',
        'email',
        'password',
        'profile_img',
        'user_id'
    ];
    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
=======
        'user_id',
        'friend_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function friend()
    {
            return $this->belongsTo(User::class, 'friend_id');
    }
}

    

>>>>>>> 7a6e651e9e5eb4d1531295815a266653383c4edb
