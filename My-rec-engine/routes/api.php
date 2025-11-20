<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InteractionController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\MLDataController;

Route::get('/users', [UserController::class, 'index']);
Route::get('/products', [ProductController::class, 'index']);
Route::post('/interactions', [InteractionController::class, 'store']);
Route::get('/recommendations/{user_id}', [RecommendationController::class, 'getRecommendations']);
Route::get('/ml-dataset', [MLDataController::class, 'export']);