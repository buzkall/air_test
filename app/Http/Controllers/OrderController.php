<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\ShopItem;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['data' => Order::with(['customer', 'items.shopItem'])->get()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.shop_item_id' => 'required|exists:shop_items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);
        $order = Order::create(['customer_id' => $validated['customer_id']]);
        foreach ($validated['items'] as $item) {
            $order->items()->create($item);
        }
        return response()->json($order->load(['customer', 'items.shopItem']), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::with(['customer', 'items.shopItem'])->findOrFail($id);
        return response()->json($order);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $order = Order::findOrFail($id);
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'items' => 'array',
            'items.*.shop_item_id' => 'required|exists:shop_items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);
        $order->update(['customer_id' => $validated['customer_id']]);
        if (isset($validated['items'])) {
            $order->items()->delete();
            foreach ($validated['items'] as $item) {
                $order->items()->create($item);
            }
        }
        return response()->json($order->load(['customer', 'items.shopItem']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        $order->items()->delete();
        $order->delete();
        return response()->noContent();
    }
}
