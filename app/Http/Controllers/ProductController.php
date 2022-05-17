<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getAllProduct(){
        return ['data' => Product::all()];
    }

    public function createNewProduct($id, Request $request){
        try{
            $user = User::where('id', $id)->first();
            if($user->role == 2){
                
            }
            else{
                return response()->json(['message' => 'Your user cannot perform this function'], 400);
            }
        }
        catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
