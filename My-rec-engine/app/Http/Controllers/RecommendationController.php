<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class RecommendationController extends Controller
{
    public function getRecommendations($user_id)
{

    $products = Product::take(5)->get(); 

    return response()->json([
        'user_id' => $user_id,
        'recommended_products' => $products
    ]);
}
}
