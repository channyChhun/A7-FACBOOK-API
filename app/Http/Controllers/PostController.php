<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\Postsource;
use App\Http\Requests\PostRequest;
use Exception;
use Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts= Post::all();
        return response()->json([
            'success' => true,
            'message' => 'Here are all of your posts',
            'data' => $posts,
        ], 200);
    }

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
    
        $post = new Post();
        $post->title = $request->title;
        $post->content = $request->input('content');
        $post->image_post = $imagePath;
        $post->user_id = $request->user_id;
        $post->save();
    
        return response()->json([
            'message' => 'Post created successfully',
            'data' => $post
        ], 201);
    }
    
    
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $posts = Post::find($id);
        return $posts ? response()->json([
            'success' => true,
            'message' => 'Resource was successfully retrieved with the id: '.$id,
            'data' => $posts
        ], 200): response()->json([
            'success' => false,
            'message' => 'Resource was not found with the id: '.$id,
        ], 404);
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'image_post' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // 'user_id' => 'required|exists:users,id',
        ]);
    
        // $post = Post::findOrFail($id);
        $post = Post::find($id);
    
        $post->title = $request->title;
        $post->content = $request->input('content');
        // $post->user_id = $request->user_id;
    
        if ($request->hasFile('image_post')) {
            // Delete the old image if it exists
            if ($post->image_post) {
                Storage::disk('public')->delete($post->image_post);
            }
            // Store the new image
            $post->image_post = $request->file('image_post')->store('image_post', 'public');
        }
    
        $post->save();
    
        return response()->json([
            'message' => 'Post updated successfully',
            'data' => $post
        ], 200);
    }
    

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

