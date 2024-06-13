<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like; 

class LikeController extends Controller
{
    public function addLike(Request $request){
        $request->validate([
            'post_id' =>'required',
            'title' =>'required',
            
        
        ]);
        $like= Like::create($request->all());
        return response($like);

    }
}
