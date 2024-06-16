<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment; 
use App\Http\Resources\CommentResource;

class CommentController extends Controller
{
     /**
     * @SWG\Get(
     *     path="/api/comment",
     *     summary="List all comments",
     *     tags={"Comments"},
     *     @SWG\Response(
     *         response=200,
     *         description="Successful response",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(
     *                 property="success",
     *                 type="boolean"
     *             ),
     *             @SWG\Property(
     *                 property="message",
     *                 type="string"
     *             ),
     *             @SWG\Property(
     *                 property="data",
     *                 type="array",
     *                 @SWG\Items(ref="#/definitions/Comment")
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request){
        $comments= Comment::all();
        return response()->json([
            'success' => true,
            'message' => 'Here are all of your posts',
           'data' => CommentResource::collection($comments),
        ], 200);
    }
    
    /**
     * @SWG\Post(
     *     path="/api/comment",
     *     summary="Create a new comment",
     *     tags={"Comments"},
     *     @SWG\Parameter(
     *         name="comment",
     *         in="body",
     *         required=true,
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(
     *                 property="comment",
     *                 type="string"
     *             ),
     *             @SWG\Property(
     *                 property="post_id",
     *                 type="integer"
     *             ),
     *             @SWG\Property(
     *                 property="user_id",
     *                 type="integer"
     *             )
     *         )
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Successful response",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(
     *                 property="message",
     *                 type="string"
     *             ),
     *             @SWG\Property(
     *                 property="data",
     *                 ref="#/definitions/Comment"
     *             )
     *         )
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Failed response",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(
     *                 property="success",
     *                 type="boolean"
     *             ),
     *             @SWG\Property(
     *                 property="message",
     *                 type="string"
     *             )
     *         )
     *     )
     * )
     */
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
    
    /**
     * @SWG\Delete(
     *     path="/api/comment/{id}",
     *     summary="Delete a comment",
     *     tags={"Comments"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Successful response",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(
     *                 property="success",
     *                 type="boolean"
     *             ),
     *             @SWG\Property(
     *                 property="message",
     *                 type="string"
     *             )
     *         )
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Failed response",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(
     *                 property="success",
     *                 type="boolean"
     *             ),
     *             @SWG\Property(
     *                 property="message",
     *                 type="string"
     *             )
     *         )
     *     )
     * )
     */
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