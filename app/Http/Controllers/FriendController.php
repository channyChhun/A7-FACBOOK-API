<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use Illuminate\Http\Request;

class FriendController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $friends= Friend::all();
        return response()->json([
            'success' => true,
            'message' => 'Here are all of your friends',
            'data' => $friends
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $friends = new Friend();
        $friends->name = $request->name;
        $friends->email = $request->email;
        $friends->password= $request->password;
        $friends->user_id = $request->user_id;
        // $friends->save();
    
        return response()->json([
            'message' => 'friend created successfully',
            'data' =>  $friends
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Friend $friend)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Friend $friend)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Friend $friend)
    {
        //
    }
}
