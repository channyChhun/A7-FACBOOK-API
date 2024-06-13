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
        $image_name='profile_img'.time().'.'.$request->profile_img->extension();
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
