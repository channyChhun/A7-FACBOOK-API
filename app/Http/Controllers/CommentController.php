<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment; 
use App\Http\Resources\CommentResource;

class CommentController extends Controller
{
    public function index(Request $request){
        $comments= Comment::all();
        return response()->json([
            'success' => true,
            'message' => 'Here are all of your posts',
           'data' => CommentResource::collection($comments),
        ], 200);
    }

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
    public function destroy(Comment $comments, $id)
    {
        $comments = Comment::find($id);
        $comments = $comments ? $comments->delete() : false;

        return $comments ? response()->json([
            'success' => true,
            'message' => 'comment deleted successfully',
        ], 200): response()->json([
            'success' => false,
            'message' => 'Failed to delete the post',
        ], 404);
    }
        
   
}