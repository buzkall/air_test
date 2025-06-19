<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShopItemCategory;

class ShopItemCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['data' => ShopItemCategory::all()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
        ]);
        $category = ShopItemCategory::create($validated);
        return response()->json($category, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = ShopItemCategory::findOrFail($id);
        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = ShopItemCategory::findOrFail($id);
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
        ]);
        $category->update($validated);
        return response()->json($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = ShopItemCategory::findOrFail($id);
        $category->delete();
        return response()->noContent();
    }
}
