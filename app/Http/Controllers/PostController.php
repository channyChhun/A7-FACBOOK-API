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
        $posts = Post::create($request->all());
        return response()->json([
           'success' => true,
           'message' => 'Post created successfully',
            'data' => $posts,
        ], 201);
    
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $posts)
    {

        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $posts)
    {
        $posts->update($request->all());
    
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
            'data' => $posts,
        ], 200);
    
    }
}
