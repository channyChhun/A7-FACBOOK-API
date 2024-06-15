<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use App\Http\Resources\LikeResource;
use Illuminate\Support\Facades\Log;
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
        // Log the incoming request data
        Log::info('Incoming request data:', $request->all());

        // Validate the request
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'post_id' => 'required|exists:posts,id',
        ]);

        // Log the validated data
        Log::info('Validated data:', $validatedData);

        // Check if the like already exists
        $existingLike = Like::where('user_id', $validatedData['user_id'])
                            ->where('post_id', $validatedData['post_id'])
                            ->first();

        if ($existingLike) {
            // If like exists, remove it (unlike)
            $existingLike->delete();

            return response()->json([
                'message' => 'Post unliked successfully',
            ], 200);
        } else {
            // If like does not exist, create a new like
            $like = new Like();
            $like->user_id = $validatedData['user_id'];
            $like->post_id = $validatedData['post_id'];
            $like->save();

            return response()->json([
                'message' => 'Post liked successfully',
                'data' => $like
            ], 200);
        }
    }
}
