<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Product;
use App\Models\Review;
use App\Models\Order;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function getAllProduct(){
        try{
            return ['data' => Product::all()];

        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 400);
        }
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

    public function getAllProduct3(){
        try{
            $listProduct = [];
            foreach(Product::all() as $product){
                $company = Company::where('id', $product->id_company)->first();
                $listReview = Review::where('id_product', $product->id)->get();
                $listRate = [];
                if($listReview){
                    foreach($listReview as $review){
                        array_push($listRate, $review->star_rating);
                    }
                }
                array_push($listProduct, [
                    'product' => $product,
                    'company' => $company->name,
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

    public function getDetailProduct2(Request $request){
        try{
            $userReview = 'denied';
            $orderList = Order::where('id_user',$request->id_user)->where('is_ordered', true)->get();
                foreach($orderList as $order){
                    foreach($order->product as $product){
                        if($product->id == $request->id_product){
                            $userReview ='approved';
                            break 2;
                        }
                    }
                }
            $product = Product::where('id', $request->id_product)->first();
            $company = Company::where('id', $product->id_company)->first();
            $listReview = Review::where('id_product', $request->id_product)->get();
            $arrayReview = [];
            if($listReview){
                foreach($listReview as $review){
                    $user = User::where('id', $review->id_user)->first();
                    if($user->id == $request->id_user){
                        $userReview='denied';
                    }
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
                'list_review' => $arrayReview,
                'review' => $userReview
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

    public function createNewProduct(Request $request){
        try{
            Validator::make($request->all(),[
                'name' => 'required|max:255',
                'description' => 'required|max:1024',
                'price' => 'required|min:1',
                'amount_sold' => 'required|min:0',
                'amount_remaining' => 'required|min:1',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);

            $user = User::where('id', $request->id_user)->first();
            if($user->role == 2){
                $company = Company::where('id_user', $request->id_user)->first();

                if ($request->hasFile('image')) {
                    $link = Storage::disk('s3')->put('images/products', $request->image);
                    $link = Storage::disk('s3')->url($link);
                }

                $product = Product::create([
                    'id_company' => $company->id,
                    'name' => $request->name,
                    'description' => $request->description,
                    'price' => $request->price,
                    'amount_sold' => $request->amount_sold,
                    'amount_remaining' => $request->amount_remaining,
                    'image' => $link
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

    public function getProductByCompany(Request $request){
        $company = Company::where('id_user', $request->id_user)->first();
        return Product::where('id_company', $company->id)->get();
    }

    public function editProduct(Request $request){
        try{
            $user = User::where('id', $request->id_user)->first();
            if($user->role != 1){
                $product = Product::where('id', $request->id_product)->first();
                $company = Company::where('id_user', $request->id_user)->first();
                if($company->id == $product->id_company || $user->role == 0){
                    Validator::make($request->all(),[
                        'name' => 'required|max:255',
                        'description' => 'required|max:1024',
                        'price' => 'required|min:1',
                        'amount_remaining' => 'required|min:1',
                        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
                    ]);
                    $link = $product->image;
                    if ($request->hasFile('image')) {
                        $link = Storage::disk('s3')->put('images/products', $request->image);
                        $link = Storage::disk('s3')->url($link);
                    }

                    Product::where('id', $request->id_product)->update([
                        'name' => $request->name,
                        'description' => $request->description,
                        'price' => $request->price,
                        'amount_remaining' => $request->amount_remaining,
                        'image' => $link
                    ]);

                    return Product::where('id', $request->id_product)->first();
                }
                else{
                    return response()->json(['message' => 'You do not own this product'], 400);
                }
            }
            else{
                return response()->json(['message' => 'Your user cannot perform this function'], 400);
            }
        }
        catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function deleteProduct(Request $request){
        try{
            $user = User::where('id', $request->id_user)->first();
            if($user->role == 2){
                $product = Product::where('id', $request->id_product)->first();
                $company = Company::where('id_user', $request->id_user)->first();
                if($company->id == $product->id_company){
                    $product->delete();
                    return response()->json(['message' => 'Success']);
                }
                else{
                    return response()->json(['message' => 'You do not own this product'], 400);
                }
            }
            elseif($user->role == 0){
                $product = Product::where('id', $request->id_product)->first();
                $product->delete();
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


    public function searchProduct(Request $request){
        try{
            $listProduct = Product::where('name', 'like', '%'.$request->key.'%')->get();
            return $listProduct;
        }
        catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}

