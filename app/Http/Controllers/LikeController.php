<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use App\Http\Resources\LikeResource;
class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $likes= Like::all();
        return response()->json([
            'success' => true,
            'message' => 'Here are all of your likes',
            'data' => LikeResource::collection($likes),
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user_id = $request->user_id;
        $post_id = $request->post_id;
        $likes = new Like();
        $likes->user_id = $user_id;
        $likes->post_id = $post_id;
        $likes->save();
        return response()->json([
            'success' => true,
            'message' => 'You been like successfully: ',
            'data' => new LikeResource($likes)
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $likes = Like::find($id);
        return $likes ? response()->json([
            'success' => true,
            'message' => 'Resource was successfully retrieved with the id: '.$id,
            'data' => $likes
        ], 200): response()->json([
            'success' => false,
            'message' => 'Resource was not found with the id: '.$id,
        ], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Like $like)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Like $like)
    {
        //
    }
}
