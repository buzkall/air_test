<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ShopItemCategoryController;
use App\Http\Controllers\Api\ShopItemController;
use App\Http\Controllers\Api\OrderController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API Routes for CRUD operations
Route::apiResource('customers', CustomerController::class);
Route::apiResource('shop-item-categories', ShopItemCategoryController::class);
Route::apiResource('shop-items', ShopItemController::class);
Route::apiResource('orders', OrderController::class);
