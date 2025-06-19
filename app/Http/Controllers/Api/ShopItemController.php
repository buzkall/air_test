<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShopItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ShopItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $shopItems = ShopItem::with('categories')->paginate(15);
        
        return response()->json($shopItems);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:shop_item_categories,id',
        ]);

        $shopItem = ShopItem::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'price' => $validated['price'],
        ]);

        if (isset($validated['category_ids'])) {
            $shopItem->categories()->sync($validated['category_ids']);
        }

        $shopItem->load('categories');

        return response()->json($shopItem, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ShopItem $shopItem): JsonResponse
    {
        $shopItem->load('categories', 'orderItems.order.customer');
        
        return response()->json($shopItem);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShopItem $shopItem): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'price' => 'sometimes|required|numeric|min:0',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:shop_item_categories,id',
        ]);

        $shopItem->update([
            'title' => $validated['title'] ?? $shopItem->title,
            'description' => $validated['description'] ?? $shopItem->description,
            'price' => $validated['price'] ?? $shopItem->price,
        ]);

        if (isset($validated['category_ids'])) {
            $shopItem->categories()->sync($validated['category_ids']);
        }

        $shopItem->load('categories');

        return response()->json($shopItem);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShopItem $shopItem): JsonResponse
    {
        $shopItem->delete();

        return response()->json(['message' => 'Shop item deleted successfully']);
    }
}