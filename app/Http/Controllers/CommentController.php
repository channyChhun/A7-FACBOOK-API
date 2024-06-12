<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment; 

class CommentController extends Controller
{
    public function addComment(Request $request){
        $request->validate([
            'post_id' =>'required',
            'title' =>'required',
        
        ]);
        $comment= Comment::create($request->all());
        return response($comment);

    }
}