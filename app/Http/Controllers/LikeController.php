<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use App\Http\Resources\LikeResource;
use Illuminate\Support\Facades\Log;
/**
 * @OA\Get(
 *     path="/api/like",
 *     summary="Retrieve a user's likes",
 *     @OA\Parameter(
 *         name="user_id",
 *         in="query",
 *         description="User ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="Successful response",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 type="object",
 *                 @OA\Property(property="id", type="integer"),
 *                 @OA\Property(property="user_id", type="integer"),
 *                 @OA\Property(property="liked_entity_id", type="integer"),
 *                 @OA\Property(property="created_at", type="string", format="date-time"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time")
 *             )
 *         )
 *     ),
 *     @OA\Response(response="404", description="User not found")
 * )
 */
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
 * @OA\Get(
 *     path="/api/like/{id}",
 *     summary="Retrieve a like",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Like ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="Successful response",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="id", type="integer"),
 *             @OA\Property(property="user_id", type="integer"),
 *             @OA\Property(property="liked_entity_id", type="integer"),
 *             @OA\Property(property="created_at", type="string", format="date-time"),
 *             @OA\Property(property="updated_at", type="string", format="date-time")
 *         )
 *     ),
 *     @OA\Response(response="404", description="Like not found")
 * )
 */
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
    
   /**
 * @OA\Post(
 *     path="/api/like",
 *     summary="Create a new like",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="user_id", type="integer", example=1),
 *             @OA\Property(property="liked_entity_id", type="integer", example=10)
 *         )
 *     ),
 *     @OA\Response(
 *         response="201",
 *         description="Successful response",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="id", type="integer"),
 *             @OA\Property(property="user_id", type="integer"),
 *             @OA\Property(property="liked_entity_id", type="integer"),
 *             @OA\Property(property="created_at", type="string", format="date-time"),
 *             @OA\Property(property="updated_at", type="string", format="date-time")
 *         )
 *     ),
 *     @OA\Response(response="400", description="Invalid input")
 * )
 */ 
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
