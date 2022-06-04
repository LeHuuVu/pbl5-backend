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
                $productList = [];
        
                for($i = 0; $i < count($request->id_product); $i++){
                    $product = Product::where('id', $request->id_product[$i])->first();
                    if($product->amount_remaining < $request->amount_order[$i]){
                        return response()->json(['message' => 'Not Enough Available'], 400);
                    }
                    array_push($amountList, $request->amount_order[$i]);
                    array_push($productList, $product);
                    $totalPrice = $product->price * $request->amount_order[$i];
    
                    $amountRemaining = $product->amount_remaining - $request->amount_order[$i];
                    $amountSold = $product->amount_sold + $request->amount_order[$i];
                    Product::where('id', $product->id)->update([
                        'amount_remaining' => $amountRemaining,
                        'amount_sold' => $amountSold
                    ]);       
                }

                for($i = 0; $i < count($request->id_product); $i++){
                    $orders->product()->save($productList[$i],[
                        'amount' => $amountList[$i],
                        'total_price' => $totalPrice
                    ]);
                }
                
                return response()->json(['message' => 'Success']);
            }
            else{
                return response()->json(['message' => 'Not find your user'], 400);
            }

        }
        catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function order2(Request $request){
        try{
            $order = Order::where('id_user', $request->id_user)->latest('id')->first();
            if($order){
                $totalPrice = 0;
                $amountList = [];
                $productList = [];

                for($i = 0; $i < count($request->id_product); $i++){
                    $product = Product::where('id', $request->id_product[$i])->first();

                    array_push($amountList, $request->amount_order[$i]);
                    array_push($productList, $product);
                    $totalPrice = $product->price * $request->amount_order[$i];
    
                    $amountRemaining = $product->amount_remaining - $request->amount_order[$i];
                    $amountSold = $product->amount_sold + $request->amount_order[$i];
                    Product::where('id', $product->id)->update([
                        'amount_remaining' => $amountRemaining,
                        'amount_sold' => $amountSold
                    ]);       
                }

                $i = 0;
                foreach($order->product as $product){
                    $product->pivot->amount = $amountList[$i++];
                    $product->pivot->total_price = $totalPrice;
                    $product->pivot->save();
                }

                $order->delivery_address = $request->delivery_address;
                $order->delivery_time = $request->delivery_time;
                $order->is_ordered = true;
                $order->save();
                
                return response()->json(['message' => 'Success']);

            }
            else{
                return response()->json(['message' => "You don't have any items in your cart"], 400);
            }
            
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function addToCart(Request $request){
        try{
            $product = Product::where('id', $request->id_product)->first();
            $order = Order::where('id_user', $request->id_user)->latest('id')->first();
            if($order){
                if($order->is_ordered){
                    $newOrder = Order::create(
                        [
                            'id_user' => $request->id_user,
                            'is_ordered' => false,
                            'delivery_address' => '',
                            'delivery_time' => date('Y-m-d H:i:s'),
                        ]
                    );
                    $newOrder->product()->save($product,[
                        'amount' => 0,
                        'total_price' => 0
                    ]);
                }
                else{
                    $is_existed = false;
                    foreach($order->product as $order_product){
                        if($order_product->id == $product->id){
                            $is_existed = true;
                        }
                    }
                    if(!$is_existed){
                        $order->product()->save($product,[
                            'amount' => 0,
                            'total_price' => 0
                        ]);
                    }
                    else{
                        return response()->json(['message' => "This product already exists in the cart"], 400);
                    }
                }
            }
            else{
                $newOrder = Order::create(
                    [
                        'id_user' => $request->id_user,
                        'is_ordered' => false,
                        'delivery_address' => '',
                        'delivery_time' => date('Y-m-d H:i:s'),
                    ]
                );
                $newOrder->product()->save($product,[
                    'amount' => 0,
                    'total_price' => 0
                ]);
            }

            return response()->json(['message' => 'Success']);
        }
        catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function takeOutFromCart(Request $request){
        try {
            $order = Order::where('id_user', $request->id_user)->where('is_ordered', false)->first();
            $order->product()->detach($request->id_product);
            return response()->json(['message' => 'Success']);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function getCart(Request $request){
        try{
            $order = Order::where('id_user', $request->id_user)->where('is_ordered', false)->first();
            if($order){
                $listProduct = [];
                foreach($order->product as $product){
                    array_push($listProduct, $product);
                }
            }
            else{
                return response()->json(['message' => "You don't have any items in your cart"]);
            }

            return ['data' => $listProduct];

        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
