<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getAllProduct(){
        return ['data' => Product::all()];
    }

    public function createNewProduct($id, Request $request){
        $validate = Validator::make($request->all(),[
            'name' => 'required|max:255',
            'description' => 'required|max:1024',
            'price' => 'required|min:1',
            'amount_sold' => 'required|min:0',
            'amount_remaining' => 'required|min:1',
            'image' => 'required'
        ]);
        
        if($validate->failed()){
            return $validate->errors();
        }

        try{
            $user = User::where('id', $id)->first();
            if($user->role == 2){
                $product = Product::create([
                    'id_company' => $request->id_company,
                    'name' => $request->name,
                    'description' => $request->description,
                    'price' => $request->price,
                    'amount_sold' => $request->amount_sold,
                    'amount_remaining' => $request->amount_remaining,
                    'image' => $request->file('image')->store('public/products')
                ]);
                $product->save();
                return $product;
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
