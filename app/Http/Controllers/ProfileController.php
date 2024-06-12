<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Exception;
class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user=User::all();
        return response()->json(["data"=>$user,"message"=>"sucessfuly"],200);
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        return response()->json(["success"=>true, "data"=>$user], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $name=$request->name;
        $email=$request->email;
        $password=$request->password;
        $users=User::find($id);
        $users->name=$name;
        $users->email=$email;
        $users->password=$password;
        $users->save();
        try{
            $users->save();
            return response()->json(["data"=>$users,"message"=>"update sucessfuly"],200);
        }catch(Exception $erorr){
            return response()->json(["data"=>$users,"message"=>"Failed to update this users"],500);
        }

        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
