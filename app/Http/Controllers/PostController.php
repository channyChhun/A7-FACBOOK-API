<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\Postsource;
use App\Http\Requests\PostRequest;

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
   
}
