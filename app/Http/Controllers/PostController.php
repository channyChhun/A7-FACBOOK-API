<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::all();
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
            'title' =>'required',
            'content' =>'required',
        ]);
        $posts = Post::create([
            'title'=>$request->title,
            'content'=>$request->content,
            'user_id'=>$request->user_id,


        ]);
        return response()->json([
            'success' => true,
            'message' => 'Post created successfully',
            'data' => $posts,
        ], 201);



        
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $posts = Post::find($id);
        return response()->json([
            'success' => true,
            'message' => 'Resource was successfully retrieved with the id: '.$id,
            'data' => $posts
    ], 200);
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $posts)
    {
        $request->validate([
            'title' => 'sometimes|required',
            'content' => 'sometimes|required',
        ]);

        $posts->update($request->only(['title', 'content']));

        return response()->json([
            'success' => true,
            'message' => 'Post updated successfully',
            'data' => $posts,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $posts)
    {
        $posts->delete();

        return response()->json([
            'success' => true,
            'message' => 'Post deleted successfully',
        ], 200);
    }
}