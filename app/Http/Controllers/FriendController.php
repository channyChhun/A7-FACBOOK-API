<?php
namespace App\Http\Controllers;

use App\Models\FriendRequest;
use App\Models\Friend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
/**
 * @OA\Tag(
 *     name="Friend",
 *     description="Operations related to friend management"
 * )
 */

class FriendController extends Controller
{
    
    /**
     * @OA\Post(
     *     path="/api/friends/request",
     *     summary="Send a friend request",
     *     tags={"Friend"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="recipient_id",
     *                     type="integer"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Friend request sent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error occurred while sending friend request",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function sendRequest(Request $request)
    {
        $request->validate([
            'recipient_id' => 'required|exists:users,id',
        ]);

        $recipientId = $request->recipient_id;
        $senderId = auth()->id();

        if ($senderId == $recipientId) {
            return response()->json(['message' => 'You cannot send a friend request to yourself.'], 400);
        }

        $existingRequest = FriendRequest::where('sender_id', $senderId)
                                         ->where('recipient_id', $recipientId)
                                         ->first();

        if ($existingRequest) {
            return response()->json(['message' => 'Friend request already sent.'], 400);
        }

        FriendRequest::create([
            'sender_id' => $senderId,
            'recipient_id' => $recipientId,
        ]);

        return response()->json(['message' => 'Friend request sent successfully.'], 200);
    }
     /**
     * @OA\Post(
     *     path="/api/friends/{id}/accept",
     *     summary="Accept a friend request",
     *     tags={"Friend"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Friend request accepted",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Friend request not found or already processed",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */

    public function acceptRequest($id)
    {
        $userId = auth()->id();

        // Find the pending friend request for the authenticated user
        $friendRequest = FriendRequest::where('recipient_id', auth()->id())
        ->where('id', $id)
        ->where('status', 'pending')
        ->first();

        if (!$friendRequest) {
            return response()->json(['message' => 'Friend request not found or already processed.'], 404);
        }

        // Update the status to accepted
        $friendRequest->update(['status' => 'accepted']);

        // Create the friend relationships
        Friend::create([
            'user_id' => $friendRequest->sender_id,
            'friend_id' => $friendRequest->recipient_id,
        ]);

        Friend::create([
            'user_id' => $friendRequest->recipient_id,
            'friend_id' => $friendRequest->sender_id,
        ]);

        return response()->json(['message' => 'Friend request accepted.'], 200);
    }
     /**
     * @OA\Post(
     *     path="/api/friends/{id}/reject",
     *     summary="Reject a friend request",
     *     tags={"Friend"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Friend request rejected",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Friend request not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */

    public function rejectRequest($id)
    {
        try {
            $friendRequest = FriendRequest::where('recipient_id', auth()->id())
                                          ->where('id', $id)
                                          ->firstOrFail();
    
            $friendRequest->update(['status' => 'rejected']);
    
            return response()->json(['message' => 'Friend request rejected.'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Friend request not found.'], 404);
        }
    }

    public function pendingRequests()
    {
        $pendingRequests = FriendRequest::where('recipient_id', auth()->id())
                                        ->where('status', 'pending')
                                        ->get();

        return response()->json($pendingRequests, 200);
    }

    public function sentRequests()
    {
        $sentRequests = FriendRequest::where('sender_id', auth()->id())
                                     ->where('status', 'pending')
                                     ->get();

        return response()->json($sentRequests, 200);
    }

    public function friendsList(Request $request)
    {
        $userId = auth()->id();

        // Retrieve the friends for the authenticated user
        $friends = Friend::where('user_id', $userId)
                         ->with('friend') 
                         ->get();

        return response()->json($friends);
    }
    
  
    public function friendShow($friendId)
    {
        $userId = auth()->id();

        // Retrieve the specific friend for the authenticated user
        $friend = Friend::where('user_id', $userId)
                        ->where('friend_id', $friendId)
                        ->with('friend') 
                        ->first();

        if (!$friend) {
            return response()->json(['message' => 'Friend not found'], 404);
        }

        return response()->json($friend);
    }
    /**
     * @OA\Delete(
     *     path="/api/friends/{id}",
     *     summary="Remove a friend",
     *     tags={"Friend"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Friend deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function removeFriend($friendId)
    {
        $userId = auth()->id();

        // Delete the friend relationship
        DB::transaction(function () use ($userId, $friendId) {
            Friend::where('user_id', $userId)
                  ->where('friend_id', $friendId)
                  ->delete();

            Friend::where('user_id', $friendId)
                  ->where('friend_id', $userId)
                  ->delete();
        });

        return response()->json(['message' => 'Friend deleted successfully.'], 200);
    }
}
