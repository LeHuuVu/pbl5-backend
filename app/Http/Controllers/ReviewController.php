<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function review(Request $request){
        try{
            if(Review::where('id_user',$request->id_user)->where('id_product',$request->id_product)->first()){
                Review::where('id_user',$request->id_user)
                    ->where('id_product',$request->id_product)
                    ->update([
                        'star_rating' => $request->star_rating,
                        'comment' => $request->comment,
                        'time' => date('Y-m-d H:i:s')
                    ]);
                return response()->json(['message' => 'Edit complete']);
            }
            else{
                $orderList = Order::where('id_user',$request->id_user)->get();
                foreach($orderList as $order){
                    foreach($order->product as $product){
                        if($product->id == $request->id_product){
                            $review = Review::create([
                                'id_user' => $request->id_user,
                                'id_product' => $request->id_product,
                                'star_rating' => $request->star_rating,
                                'comment' => $request->comment,
                                'time' => date('Y-m-d H:i:s')
                            ]);
                            $review->save();
                            return response()->json(['message' => 'success']);
                        }
                    }
                }
    
                return response()->json(
                    ['message' => 'This product has not been purchased by you, please buy before rating'], 400
                );
            }
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
