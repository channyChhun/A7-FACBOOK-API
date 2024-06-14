<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use App\Http\Resources\LikeResource;
use App\Http\Requests\LikeRequest;
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
     * Display the specified resource.
     */
    public function show($id)
    {
        $likes = Like::find($id);
        return $likes ? response()->json([
            'success' => true,
            'message' => 'Like was successfully retrieved with the id: '.$id,
            'data' =>new LikeResource($likes)
        ], 200): response()->json([
            'success' => false,
            'message' => 'Like was not found with the id: '.$id,
        ], 404);
    }
    
    
    public function store(LikeRequest $request)
    {
        $likes = Like::storeOrUpate($request);
        return response()->json([
            'success' => true,
            'message' => 'You successfully created a new post',
            'data' => new LikeResource($likes),
        ], 200);
    }
   

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Like $like)
    {
        $like = $like->delete();
        return $like? response()->json([
           'success' => true,
           'message' => 'Like was successfully deleted',
        ], 200): response()->json([
           'success' => false,
           'message' => 'Like was not found with the id: '.$id,
        ], 404);
    }
}
