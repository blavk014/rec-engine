<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Product;
use App\Models\Interaction;
use Illuminate\Support\Facades\DB;


use Illuminate\Http\Request;


class MLDataController extends Controller
{
    public function getUsers() {
      
        return response()->json(User::all());
    }

     public function getProducts() {
        return response()->json(Product::all());
     }
      public function getInteractions() {
        return response()->json(Interaction::all());
      }

public function export()
{
    $rows = DB::table('interactions as i')
        ->leftJoin('users as u', 'u.id', '=', 'i.user_id')
        ->leftJoin('products as p', 'p.id', '=', 'i.product_id')
        ->selectRaw("
            i.user_id,
            i.product_id,
            i.action as interaction_action,
            CASE i.action
                WHEN 'view' THEN 1.0
                WHEN 'click' THEN 2.0
                WHEN 'purchase' THEN 5.0
                ELSE 0.0
            END as interaction_weight,
            i.created_at as interaction_timestamp,
            u.created_at as user_created_at,
            SUBSTRING_INDEX(u.email, '@', -1) as user_email_domain,
            p.name as product_name,
            p.category as product_category,
            p.price as product_price
        ")
        ->orderBy('i.created_at', 'asc')
        ->get();

    return response()->json($rows);
}

}
