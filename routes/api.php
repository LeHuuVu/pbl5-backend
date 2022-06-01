<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//UserController
Route::post('v1/login',[UserController::class,'login']);
Route::post('v1/register',[UserController::class,'register']);
Route::post('v2/register',[UserController::class,'registerV2']);

//ProductController
Route::get('v1/getAllProduct',[ProductController::class,'getAllProduct']);
Route::get('v2/getAllProduct',[ProductController::class,'getAllProduct2']);
Route::post('v1/getDetailProduct',[ProductController::class,'getDetailProduct']);
Route::post('v1/createNewProduct/{id}',[ProductController::class,'createNewProduct']);
Route::post('v1/getReviewProduct',[ProductController::class,'getReviewProduct']);

//OrderController
Route::post('v1/order/{id}', [OrderController::class,'order']);
Route::post('v2/order', [OrderController::class,'order2']);
Route::post('v1/addToCart',[OrderController::class,'addToCart']);
Route::post('v1/takeOutFromCart',[OrderController::class,'takeOutFromCart']);
Route::post('v1/getCart',[OrderController::class,'getCart']);

//ReviewController
Route::post('v1/review',[ReviewController::class,'review']);

//CategoryController
Route::post('v1/getProductByCategory',[CategoryController::class,'getProductByCategory']);

//fileStorage
Route::get('/artisan/storage', function() {
    $command = 'storage:link';
    $result = Artisan::call($command);
    return Artisan::output();
});
