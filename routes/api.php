<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('customers', App\Http\Controllers\CustomerController::class);
Route::apiResource('shop-item-categories', App\Http\Controllers\ShopItemCategoryController::class);
Route::apiResource('shop-items', App\Http\Controllers\ShopItemController::class);
Route::apiResource('orders', App\Http\Controllers\OrderController::class);
