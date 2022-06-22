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
                'avatar' => 'https://pbl5-backend.herokuapp.com/avatar/default.png',
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
            $link='https://pbl5-backend.herokuapp.com/avatar/default.png';
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
            if ($request->has('id_user')){
                if($request->has('name')){
                    User::where('id', $request->id_user)->update(['name'=>$request->name]);
                }
                if($request->has('phone')){
                    User::where('id', $request->id_user)->update(['name'=>$request->phone]);
                }
                if($request->has('address')){
                    User::where('id', $request->id_user)->update(['name'=>$request->address]);
                }
                if ($request->hasFile('avatar')) {
                    $user = User::where('id', $request->id_user)->first();
                    if(strlen($user->avatar) > 0){
                        $element = explode("/", $user->avatar);
                        $path = 'images/avatars/'.$element[5];
                        Storage::disk('s3')->delete($path);
                    }
                    $link = Storage::disk('s3')->put('images/avatars', $request->avatar);
                    $link = Storage::disk('s3')->url($link);
                    User::where('id', $request->id_user)->update(['avatar'=>$link]);
                }
                return User::where('id', $request->id_user)->first();
            }
            else{return response()->json(['message' => "Can't find user"], 404);}

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

            $link = 'https://pbl5-backend.herokuapp.com/avatar/default.png';
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

    public function getAllUser(Request $request){
        try{
            if(User::where('id', $request->id_user)->first()->role == 0){
                return User::all();
            }
            else{
                return response()->json(['message' => 'Your user cannot perform this function'], 400);
            }
        }
        catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function deleteUser(Request $request){
        try{
            if(User::where('id', $request->id_user)->first()->role == 0){
                User::where('id', $request->id_user_delete)->delete();
                return response()->json(['message' => 'Success']);
            }
            else{
                return response()->json(['message' => 'Your user cannot perform this function'], 400);
            }
        }
        catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function getCompany(Request $request){
        try{
            $company = Company::where('id_user', $request->id_user)->first();
            return $company;
        }
        catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
