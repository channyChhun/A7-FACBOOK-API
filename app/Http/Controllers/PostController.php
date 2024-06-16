<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use Exception;
use Storage;

class PostController extends Controller
{
    /**
 * @OA\Info(
 *     title="Blog API",
 *     version="1.0.0",
 *     description="API for managing blog posts"
 * )
 */

/**
 * @OA\Get(
 *     path="/api/post",
 *     summary="Get all posts",
 *     @OA\Response(
 *         response="200",
 *         description="Successful response",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="success",
 *                 type="boolean"
 *             ),
 *             @OA\Property(
 *                 property="message",
 *                 type="string"
 *             ),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/Post")
 *             )
 *         )
 *     )
 * )
 */
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts= Post::all();
        return response()->json([
            'success' => true,
            'message' => 'Here are all of your posts',
           'data' => PostResource::collection( $posts),
        ], 200);
    }

/**
 * @OA\Post(
 *     path="/api/post",
 *     summary="Create a new post",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/PostInput")
 *     ),
 *     @OA\Response(
 *         response="201",
 *         description="Successful response",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 type="string"
 *             ),
 *             @OA\Property(
 *                 property="data",
 *                 ref="#/components/schemas/Post"
 *             )
 *         )
 *     )
 * )
 */
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'image_post' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'user_id' => 'required|exists:users,id',
        ]);
    
        $imagePath = null;
        if ($request->hasFile('image_post')) {
            $imagePath = $request->file('image_post')->store('image_post', 'public');
        }
    
        $posts = new Post();
        $posts->title = $request->title;
        $posts->content = $request->input('content');
        $posts->image_post = $imagePath;
        $posts->user_id = $request->user_id;
        $posts->save();
    
        return response()->json([
            'message' => 'Post created successfully',
            'data' => $posts
        ], 201);
    }
    
    
/**
 * @OA\Get(
 *     path="/api/post/{id}",
 *     summary="Get a post",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Post ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="Successful response",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="success",
 *                 type="boolean"
 *             ),
 *             @OA\Property(
 *                 property="message",
 *                 type="string"
 *             ),
 *             @OA\Property(
 *                 property="data",
 *                 ref="#/components/schemas/Post"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response="404",
 *         description="Post not found"
 *     )
 * )
 */
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $posts = Post::find($id);
        return $posts ? response()->json([
            'success' => true,
            'message' => 'Resource was successfully retrieved with the id: '.$id,
            'data' =>new PostResource($posts)
        ], 200): response()->json([
            'success' => false,
            'message' => 'Resource was not found with the id: '.$id,
        ], 404);
        
    }

/**
 * @OA\Put(
 *     path="/api/post/{id}",
 *     summary="Update a post",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Post ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/PostInput")
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="Successful response",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 type="string"
 *             ),
 *             @OA\Property(
 *                 property="data",
 *                 ref="#/components/schemas/Post"
 *             )
 *         )
 *     )
 * )
 */
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'image_post' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $posts = Post::find($id);
    
        $posts->title = $request->title;
        $posts->content = $request->input('content');
    
        if ($request->hasFile('image_post')) {
            if ($posts->image_post) {
                Storage::disk('public')->delete($posts->image_post);
            }
            // Store the new image
            $posts->image_post = $request->file('image_post')->store('image_post', 'public');
        }
    
        $posts->save();
    
        return response()->json([
            'message' => 'Post updated successfully',
            'data' => $posts
        ], 200);
    }
   /**
 * @OA\Schema(
 *     schema="Post",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="content", type="string"),
 *     @OA\Property(property="image_post", type="string"),
 *     @OA\Property(property="user_id", type="integer"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

/**
 * @OA\Schema(
 *     schema="PostInput",
 *     type="object",
 *     required={"title", "user_id"},
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="content", type="string"),
 *     @OA\Property(property="image_post", type="string", format="binary"),
 *     @OA\Property(property="user_id", type="integer")
 * )
 */
 

    /**
     * Remove the specified resource from storage.
     */

     public function destroy(Post $posts, $id)
     {
         $posts = Post::find($id);
         $posts = $posts ? $posts->delete() : false;
 
         return $posts ? response()->json([
             'success' => true,
             'message' => 'Post deleted successfully',
         ], 200): response()->json([
             'success' => false,
             'message' => 'Failed to delete the post',
         ], 404);
     }
 
}
