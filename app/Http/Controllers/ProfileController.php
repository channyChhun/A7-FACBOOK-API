<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Validator;
use Hash;
use file;
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
        $validator=Validator::make($request->all(),[
            'name'=>'required',
            'profile_img'=>'nullable|image|mimes:jpg,bmp,png',

        ]);
        if($validator->fails()){
            return response()->json(["message"=>'Validation fails','error'=>$validator->errors()],400);
        }
        $user=$request->user();
        if($request->hasFile('profile_img')){
            if($user->profile_img){
                $old_part=public_path().'/upload/'
                .$user->profile_img;
                if(File::exists($old_part)){
                    File::delete($old_part);
    
            }   
        }
        $image_name='profile_img'.time().'.'.$request->
        profile_img->extension();
        $request->profile_img->move(public_path('/upload/'),$image_name);

    }
    else {
        $image_name=$user->profile_img;

    }
    $user->update([
        'name'=>$request->name,
        'profile_img'=>$image_name,
    ]);
    return response()->json(['message'=>"profile successfully updated"],200);
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
    // public function update(Request $request, string $id)
    // {
    //     $name=$request->name;
    //     $email=$request->email;
    //     $password=$request->password;
    //     $profile_img=$request->profile_img;
    //     $users=User::find($id);
    //     $users->name=$name;
    //     $users->email=$email;
    //     $users->password=$password;
    //     $users->profile_img=$profile_img;
    //     $users->save();
    //     try{
    //         $users->save();
    //         return response()->json(["data"=>$users,"message"=>"update sucessfuly"],200);
    //     }catch(Exception $erorr){
    //         return response()->json(["data"=>$users,"message"=>"Failed to update this users"],500);
    //     }

        
    // }
    public function update(Request $request, string $id)
{
    // Validate request data
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,'.$id,
        'password' => 'sometimes|string|min:8',
        'profile_img' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    $name = $request->name;
    $email = $request->email;
    $password = $request->password;
    $profile_img = $request->file('profile_img');

    $user = User::find($id);
    if (!$user) {
        return response()->json(["message" => "User not found"], 404);
    }

    $user->name = $name;
    $user->email = $email;
    if ($password) {
        $user->password = bcrypt($password);
    }

    if ($profile_img) {
        // Handle the profile image upload
        $imagePath = $profile_img->store('profile_images', 'public');
        $user->profile_img = $imagePath;
    }

    try {
        $user->save();
        return response()->json(["data" => $user, "message" => "Update successful"], 200);
    } catch (Exception $error) {
        return response()->json(["data" => $user, "message" => "Failed to update user"], 500);
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
