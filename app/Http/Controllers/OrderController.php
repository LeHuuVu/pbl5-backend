<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    
    public function order($id, Request $request){
        try{
            if(User::where('id',$id)->first()){
                $orders = Order::create(
                    [
                        'id_user' => $id,
                        'delivery_address' => $request->delivery_address,
                        'delivery_time' => $request->delivery_time,
                        'is_ordered' => true
                    ]
                );
    
                $totalPrice = 0;
                $amountList = [];
        
                for($i = 0; $i < count($request->id_product); $i++){
                    $product = Product::where('id', $request->id_product[$i])->first();
                    if($product->amount_remaining < $request->amount_order[$i]){
                        return response()->json(['message' => 'Not Enough Available'], 400);
                    }
                    array_push($amountList, $request->amount_order[$i]);
                    $totalPrice = $product->price * $request->amount_order[$i];
    
                    $amountRemaining = $product->amount_remaining - $request->amount_order[$i];
                    $amountSold = $product->amount_sold + $request->amount_order[$i];
                    Product::where('id', $product->id)->update([
                        'amount_remaining' => $amountRemaining,
                        'amount_sold' => $amountSold
                    ]);       
                }
    
                foreach($amountList as $amount){
                    $orders->product()->save($product,[
                        'amount' => $amount,
                        'total_price' => $totalPrice
                    ]);
                }
                
                return $orders;
            }
            else{
                return response()->json(['message' => 'Not find your user'], 400);
            }

        }
        catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
