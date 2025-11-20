<?php

namespace App\Http\Controllers;
use App\Models\Interaction;
use Illuminate\Http\Request;

class InteractionController extends Controller
{
    public function store(Request $request)
{
    /*
    $validated = $request->validate([
        'user_id' => 'required|exists:users,id',
        'product_id' => 'required|exists:products,id',
        'action' => 'required|in:view,click,purchase',
        'rating' => 'nullable|integer|min:1|max:5'
    ]);

    $interaction = Interaction::create($validated);

    
    return response()->json($interaction);*/
    
    $interaction = Interaction::create([
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
            'action' => $request->action,
            'rating' => $request->rating
        ]);

        return response()->json($interaction, 201);
}
}
