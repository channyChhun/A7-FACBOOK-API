<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment; 

class CommentController extends Controller
{
    public function store(Request $request)
    {
            $comments = new Comment();
            $comments->comment = $request->comment;
            $comments->post_id= $request->post_id;
            $comments->user_id= $request->user_id;
            $comments->save();
        
            return $comments ? response()->json([
                'message' => 'Comment created successfully',
                'data' => $comments
            ], 200) : response()->json([
                'success' => false,
                'message' => 'Failed to create the comment',
            ], 404);
        }
        
   
}