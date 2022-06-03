<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request){
        $user = User::all();
        foreach($user as $data){
            echo $data->email;
            echo '<br>';
        }
    }

    public function login(Request $request){
        $user = User::where('email', $request->email)->first();
        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json(['message' => 'Email or password is incorrect'], 400);
        }
        return $user;
    }

    public function register(Request $request){
        try{
            Validator::make($request->all(),[
                'name' => 'required|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
                'phone' => 'required',
                'address' => 'required',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'address' => $request->address,
                'avatar' => '',
                'role' => 1
            ]);
            $user->save();
            return $user;
        }
        catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
    public function registerV2(Request $request){
        try{
            Validator::make($request->all(),[
                'name' => 'required|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
                'phone' => 'required',
                'address' => 'required',
                'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            if ($request->hasFile('avatar')) {
                $link = Storage::disk('s3')->put('images/avatars', $request->avatar);
                $link = Storage::disk('s3')->url($link);
            }
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'address' => $request->address,
                'avatar' => $link,
                'role' => 1
            ]);
            $user->save();
            return $user;
        }
        catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function editProfile(Request $request){
        try{
            Validator::make($request->all(),[
                'name' => 'required|max:255',
                'phone' => 'required',
                'address' => 'required',
                'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            if ($request->hasFile('avatar')) {
                $link = Storage::disk('s3')->put('images/avatars', $request->avatar);
                $link = Storage::disk('s3')->url($link);
            }

            User::where('id', $request->id_user)->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
                'avatar' => $link
            ]);
            
            return User::where('id', $request->id_user)->first();

        }
        catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function registerSeller(Request $request){
        try{
            Validator::make($request->all(),[
                'name' => 'required|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
                'phone' => 'required',
                'address' => 'required',
                'company_name' => 'required'
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'address' => $request->address,
                'avatar' => '',
                'role' => 2
            ]);
            
            Company::create([
                'id_user' => $user->id,
                'name' => $request->company_name
            ]);

            return $user;
        }
        catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
