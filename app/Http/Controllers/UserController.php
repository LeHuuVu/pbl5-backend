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
                'role' => 'required'
            ]);

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
                'role' => 'required'
            ]);
            $file;
            if ($request->hasFile('avatar')) {
                // $file = $request->file('avatar');
                // $image_name = time().'.'.$file->getClientOriginalExtension();
                $path = $request->file('avatar')->storeAs(
                    'avatar', $request->name.time().'.'.$request->file('avatar')->getClientOriginalExtension()
                );
                // $destinationPath = storage_path('app/avatar');
                $link = 'https://pbl5-backend.herokuapp.com/'.$path;
            }
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'address' => $request->address,
                'avatar' => $link,
                'role' => $request->role
            ]);
            $user->save();
            return $user;
        }
        catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
