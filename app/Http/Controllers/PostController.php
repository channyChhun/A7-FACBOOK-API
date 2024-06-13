<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\Post\Postresource;
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
    public function store(Request $request)
    {
        $title = $request->title;
        $content = $request->content;
        $user_id = $request->user_id;
        $posts = new Post();
        $posts->title = $title;
        $posts->content = $content;
        $posts->user_id = $user_id;
        $posts->save();
        return response()->json([
            'success' => true,
            'message' => 'You been create post successfully: ',
        ], 200);
        

    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $posts = Post::find($id);

        if ($posts) {
            $posts->title = $request->title;
            $posts->content = $request->content;
            $posts->save();
            return response()->json([
                'success' => true,
                'message' => 'Post updated successfully',
                'data' => $posts
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Post not found',
            ], 404);
        }
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
