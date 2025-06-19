<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $orders = Order::with(['customer', 'items.shopItem'])->paginate(15);
        
        return response()->json($orders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.shop_item_id' => 'required|exists:shop_items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        return DB::transaction(function () use ($validated) {
            $order = Order::create([
                'customer_id' => $validated['customer_id'],
            ]);

            foreach ($validated['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'shop_item_id' => $item['shop_item_id'],
                    'quantity' => $item['quantity'],
                ]);
            }

            $order->load(['customer', 'items.shopItem']);

            return response()->json($order, 201);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order): JsonResponse
    {
        $order->load(['customer', 'items.shopItem']);
        
        return response()->json($order);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order): JsonResponse
    {
        $validated = $request->validate([
            'customer_id' => 'sometimes|required|exists:customers,id',
            'items' => 'sometimes|required|array|min:1',
            'items.*.shop_item_id' => 'required|exists:shop_items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        return DB::transaction(function () use ($order, $validated) {
            if (isset($validated['customer_id'])) {
                $order->update(['customer_id' => $validated['customer_id']]);
            }

            if (isset($validated['items'])) {
                // Delete existing order items
                $order->items()->delete();

                // Create new order items
                foreach ($validated['items'] as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'shop_item_id' => $item['shop_item_id'],
                        'quantity' => $item['quantity'],
                    ]);
                }
            }

            $order->load(['customer', 'items.shopItem']);

            return response()->json($order);
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order): JsonResponse
    {
        $order->delete();

        return response()->json(['message' => 'Order deleted successfully']);
    }
}