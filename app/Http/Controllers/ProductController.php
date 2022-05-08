<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getAllProduct(){
        return Product::all();
    }

    public function order(Request $request){
        // $user = User::where('id', $request->id_user)->first();
        // $myOrder = Order::create([
        //     'id_user' => $request->id_user,
        //     'delivery_address' => $request->delivery_address,
        //     'delivery_time' => $request->delivery_time,
        //     'id_order' => $request->is_order
        // ]);
        return $request->id_product->count();
    }
}
