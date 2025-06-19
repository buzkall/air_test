<?php

use App\Models\Order;
use App\Models\Customer;
use App\Models\ShopItem;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

test('can list all orders', function () {
    $response = $this->getJson('/api/orders');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'customer_id',
                    'created_at',
                    'updated_at',
                    'customer',
                    'items'
                ]
            ],
            'links'
        ]);
});

test('can show a specific order', function () {
    $order = Order::first();
    
    $response = $this->getJson("/api/orders/{$order->id}");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'id',
            'customer_id',
            'created_at',
            'updated_at',
            'customer' => [
                'id',
                'name',
                'surname',
                'email'
            ],
            'items' => [
                '*' => [
                    'id',
                    'order_id',
                    'shop_item_id',
                    'quantity',
                    'shop_item'
                ]
            ]
        ]);
});

test('can create an order with items', function () {
    $customer = Customer::first();
    $shopItems = ShopItem::take(2)->get();
    
    $orderData = [
        'customer_id' => $customer->id,
        'items' => [
            [
                'shop_item_id' => $shopItems[0]->id,
                'quantity' => 2
            ],
            [
                'shop_item_id' => $shopItems[1]->id,
                'quantity' => 1
            ]
        ]
    ];

    $response = $this->postJson('/api/orders', $orderData);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'id',
            'customer_id',
            'created_at',
            'updated_at',
            'customer',
            'items' => [
                '*' => [
                    'id',
                    'order_id',
                    'shop_item_id',
                    'quantity',
                    'shop_item'
                ]
            ]
        ]);

    $responseData = $response->json();
    expect($responseData['items'])->toHaveCount(2);
    
    $quantities = collect($responseData['items'])->pluck('quantity')->sort()->values();
    expect($quantities[0])->toBe(1);
    expect($quantities[1])->toBe(2);
});

test('can update an order customer', function () {
    $order = Order::first();
    $newCustomer = Customer::where('id', '!=', $order->customer_id)->first();
    
    $updateData = [
        'customer_id' => $newCustomer->id
    ];

    $response = $this->putJson("/api/orders/{$order->id}", $updateData);

    $response->assertStatus(200)
        ->assertJsonFragment(['customer_id' => $newCustomer->id]);

    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'customer_id' => $newCustomer->id
    ]);
});

test('can update order items', function () {
    $order = Order::first();
    $shopItems = ShopItem::take(2)->get();
    
    $updateData = [
        'items' => [
            [
                'shop_item_id' => $shopItems[0]->id,
                'quantity' => 5
            ],
            [
                'shop_item_id' => $shopItems[1]->id,
                'quantity' => 3
            ]
        ]
    ];

    $response = $this->putJson("/api/orders/{$order->id}", $updateData);

    $response->assertStatus(200);

    $order->refresh();
    expect($order->items)->toHaveCount(2);
    expect($order->items->where('shop_item_id', $shopItems[0]->id)->first()->quantity)->toBe(5);
});

test('can update both customer and items', function () {
    $order = Order::first();
    $newCustomer = Customer::where('id', '!=', $order->customer_id)->first();
    $shopItem = ShopItem::first();
    
    $updateData = [
        'customer_id' => $newCustomer->id,
        'items' => [
            [
                'shop_item_id' => $shopItem->id,
                'quantity' => 10
            ]
        ]
    ];

    $response = $this->putJson("/api/orders/{$order->id}", $updateData);

    $response->assertStatus(200)
        ->assertJsonFragment(['customer_id' => $newCustomer->id]);

    $order->refresh();
    expect($order->customer_id)->toBe($newCustomer->id);
    expect($order->items)->toHaveCount(1);
    expect($order->items->first()->quantity)->toBe(10);
});

test('can delete an order', function () {
    $order = Order::first();

    $response = $this->deleteJson("/api/orders/{$order->id}");

    $response->assertStatus(200)
        ->assertJson(['message' => 'Order deleted successfully']);

    $this->assertDatabaseMissing('orders', ['id' => $order->id]);
});

test('validates required fields when creating order', function () {
    $response = $this->postJson('/api/orders', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['customer_id', 'items']);
});

test('validates customer exists when creating order', function () {
    $shopItem = ShopItem::first();
    
    $response = $this->postJson('/api/orders', [
        'customer_id' => 999999,
        'items' => [
            [
                'shop_item_id' => $shopItem->id,
                'quantity' => 1
            ]
        ]
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['customer_id']);
});

test('validates shop items exist when creating order', function () {
    $customer = Customer::first();
    
    $response = $this->postJson('/api/orders', [
        'customer_id' => $customer->id,
        'items' => [
            [
                'shop_item_id' => 999999,
                'quantity' => 1
            ]
        ]
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['items.0.shop_item_id']);
});

test('validates quantity is positive when creating order', function () {
    $customer = Customer::first();
    $shopItem = ShopItem::first();
    
    $response = $this->postJson('/api/orders', [
        'customer_id' => $customer->id,
        'items' => [
            [
                'shop_item_id' => $shopItem->id,
                'quantity' => 0
            ]
        ]
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['items.0.quantity']);
});

test('validates items array is not empty when creating order', function () {
    $customer = Customer::first();
    
    $response = $this->postJson('/api/orders', [
        'customer_id' => $customer->id,
        'items' => []
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['items']);
});

test('returns 404 for non-existent order', function () {
    $response = $this->getJson('/api/orders/999999');

    $response->assertStatus(404);
});