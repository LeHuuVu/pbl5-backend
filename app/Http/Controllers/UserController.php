<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
            return response()->json(['error' => 'Email or password is incorrect'], 400);
        }
        return $user;
    }

    public function register(Request $request){
        $validate = Validator::make($request->all(),[
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'phone' => 'required',
            'address' => 'required',
            'role' => 'required'
        ]);

        if($validate->failed()){
            return $validate->errors();
        }

        try{
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'address' => $request->address,
                'avatar' => '',
                'role' => $request->role
            ]);
            $user->save();
            return $user;
        }
        catch(Exception $e){
            return $e;
        }
    }
}
