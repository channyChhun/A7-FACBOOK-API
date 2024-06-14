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
    
    
    public function store(Request $request)
    {
        $likes = new Like();
        $likes->user_id = $request->user_id;
        $likes->post_id= $request->post_id;
        $likes->user_id = $request->user_id;
        $likes->post_id = $request->post_id;
        $likes->save();
    
        return $likes ? response()->json([
            'message' => 'Like created successfully',
            'data' => $likes
        ], 200):response()->json([
            'success' => false,
           'message' => 'Failed to create the like',
        ] , 404);
    }
   

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $likes = Like::find($id);
        if (!$likes) {
            return response()->json([
                'success' => false,
                'message' => 'Nothing post for like',
            ], 404);
        }
        if ($likes->user_id != $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to delete this like',
            ], 403);
        }

        $likes->delete();
        return response()->json([
            'success' => true,
            'message' => 'Your like deleted successfully',
        ], 200);
    }
}

