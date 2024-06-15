<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Friend;

class FriendController extends Controller
{
    public function index()
    {
        $friends = Friend::where('user_id', auth()->id())->get();
        return response()->json($friends);
    }

    public function store(Request $request)
    {
        $friend = new Friend();
        $friend->user_id = auth()->id();
        $friend->friend_id = $request->input('friend_id');
        $friend->status = 'pending';
        $friend->save();

        return response()->json(['message' => 'Friend request sent successfully']);
    }

    public function update(Request $request, $id)
    {
        $friend = Friend::find($id);
        if ($friend) {
            $friend->status = $request->input('status');
            $friend->save();

            return response()->json(['message' => 'Friend request updated successfully']);
        }

        return response()->json(['message' => 'Friend not found'], 404);
    }

    public function destroy($id)
    {
        $friend = Friend::find($id);
        if ($friend) {
            $friend->delete();

            return response()->json(['message' => 'Friend removed successfully']);
        }

        return response()->json(['message' => 'Friend not found'], 404);
    }
}