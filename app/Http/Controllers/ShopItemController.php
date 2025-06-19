<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShopItem;
use App\Models\ShopItemCategory;

class ShopItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['data' => ShopItem::with('categories')->get()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'categories' => 'array',
            'categories.*' => 'exists:shop_item_categories,id',
        ]);
        $shopItem = ShopItem::create($validated);
        if (isset($validated['categories'])) {
            $shopItem->categories()->sync($validated['categories']);
        }
        return response()->json($shopItem->load('categories'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $shopItem = ShopItem::with('categories')->findOrFail($id);
        return response()->json($shopItem);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $shopItem = ShopItem::findOrFail($id);
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'categories' => 'array',
            'categories.*' => 'exists:shop_item_categories,id',
        ]);
        $shopItem->update($validated);
        if (isset($validated['categories'])) {
            $shopItem->categories()->sync($validated['categories']);
        }
        return response()->json($shopItem->load('categories'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $shopItem = ShopItem::findOrFail($id);
        $shopItem->delete();
        return response()->noContent();
    }
}
