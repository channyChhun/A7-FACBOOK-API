<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like; 

class LikeController extends Controller
{
    public function addLike(Request $request)
    {
        $request->validate([
            'post_id' => 'equired',
        ]);
    
        Like::create([
            'post_id' => $request->post_id,
            'user_id' => $request->user_id,
        ]);
        return response("like");
    }
}
