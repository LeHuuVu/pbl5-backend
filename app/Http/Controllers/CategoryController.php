<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function getProductByCategory(Request $request){
        $category = Category::where('id', $request->category_id)->first();
        return $category->product;
    }
}
