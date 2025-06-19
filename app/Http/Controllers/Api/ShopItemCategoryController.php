<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShopItemCategory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ShopItemCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $categories = ShopItemCategory::with('shopItems')->paginate(15);
        
        return response()->json($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $category = ShopItemCategory::create($validated);

        return response()->json($category, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ShopItemCategory $shopItemCategory): JsonResponse
    {
        $shopItemCategory->load('shopItems');
        
        return response()->json($shopItemCategory);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShopItemCategory $shopItemCategory): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
        ]);

        $shopItemCategory->update($validated);

        return response()->json($shopItemCategory);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShopItemCategory $shopItemCategory): JsonResponse
    {
        $shopItemCategory->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }
}