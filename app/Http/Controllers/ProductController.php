<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getAllProduct(){
        return ['data' => Product::all()];
    }

    public function getAllProduct2(){
        try{
            $listProduct = [];
            foreach(Product::all() as $product){
                $listReview = Review::where('id_product', $product->id)->get();
                $listRate = [];
                if($listReview){
                    foreach($listReview as $review){
                        array_push($listRate, $review->star_rating);
                    }
                }
                array_push($listProduct, [
                    'product' => $product,
                    'star_rating' => $listRate,
                ]);
            }
            
            return $listProduct;

        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 400);
        }
        
    }

    public function getDetailProduct(Request $request){
        try{
            $product = Product::where('id', $request->id_product)->first();
            $company = Company::where('id', $product->id_company)->first();
            $listReview = Review::where('id_product', $request->id_product)->get();
            $arrayReview = [];
            if($listReview){
                foreach($listReview as $review){
                    $user = User::where('id', $review->id_user)->first();
                    array_push($arrayReview, [
                        'review' => $review,
                        'user_name' => $user->name,
                        'user_avatar' => $user->avatar
                    ]);
                }
            }
            return response()->json([
                'product' => $product,
                'company_name' => $company->name,
                'list_review' => $arrayReview
            ]);
        }
        catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function getReviewProduct(Request $request){
        $review = Review::where('id_product', $request->id_product)->get();
        if($review){
            return $review;
        }else{
            return response()->json(['message' => 'This product has no reviews yet']);
        }
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
