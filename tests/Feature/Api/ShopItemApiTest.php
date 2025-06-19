<?php

use App\Models\ShopItem;
use App\Models\ShopItemCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

test('can list all shop items', function () {
    $response = $this->getJson('/api/shop-items');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'description',
                    'price',
                    'created_at',
                    'updated_at',
                    'categories'
                ]
            ],
            'links'
        ]);
});

test('can show a specific shop item', function () {
    $shopItem = ShopItem::first();
    
    $response = $this->getJson("/api/shop-items/{$shopItem->id}");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'id',
            'title',
            'description',
            'price',
            'created_at',
            'updated_at',
            'categories',
            'order_items'
        ]);
});

test('can create a shop item without categories', function () {
    $shopItemData = [
        'title' => 'New Product',
        'description' => 'A brand new product',
        'price' => 29.99
    ];

    $response = $this->postJson('/api/shop-items', $shopItemData);

    $response->assertStatus(201)
        ->assertJsonFragment([
            'title' => 'New Product',
            'description' => 'A brand new product',
            'price' => '29.99'
        ]);

    $this->assertDatabaseHas('shop_items', $shopItemData);
});

test('can create a shop item with categories', function () {
    $categories = ShopItemCategory::take(2)->pluck('id')->toArray();
    $shopItemData = [
        'title' => 'Product with Categories',
        'description' => 'A product with categories',
        'price' => 49.99,
        'category_ids' => $categories
    ];

    $response = $this->postJson('/api/shop-items', $shopItemData);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'id',
            'title',
            'description',
            'price',
            'created_at',
            'updated_at',
            'categories' => [
                '*' => [
                    'id',
                    'title',
                    'description'
                ]
            ]
        ]);

    $responseData = $response->json();
    expect($responseData['categories'])->toHaveCount(2);
});

test('validates required description when creating shop item', function () {
    $shopItemData = [
        'title' => 'Product Without Description',
        'price' => 19.99
    ];

    $response = $this->postJson('/api/shop-items', $shopItemData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['description']);
});

test('can update a shop item', function () {
    $shopItem = ShopItem::first();
    $categories = ShopItemCategory::take(1)->pluck('id')->toArray();
    $updateData = [
        'title' => 'Updated Product',
        'description' => 'Updated description',
        'price' => 99.99,
        'category_ids' => $categories
    ];

    $response = $this->putJson("/api/shop-items/{$shopItem->id}", $updateData);

    $response->assertStatus(200)
        ->assertJsonFragment([
            'title' => 'Updated Product',
            'description' => 'Updated description',
            'price' => '99.99'
        ]);

    $this->assertDatabaseHas('shop_items', [
        'id' => $shopItem->id,
        'title' => 'Updated Product',
        'price' => 99.99
    ]);
});

test('can partially update a shop item', function () {
    $shopItem = ShopItem::first();
    $updateData = ['title' => 'Partially Updated Product'];

    $response = $this->putJson("/api/shop-items/{$shopItem->id}", $updateData);

    $response->assertStatus(200)
        ->assertJsonFragment($updateData);

    $this->assertDatabaseHas('shop_items', [
        'id' => $shopItem->id,
        'title' => 'Partially Updated Product'
    ]);
});

test('can delete a shop item', function () {
    $shopItem = ShopItem::first();

    $response = $this->deleteJson("/api/shop-items/{$shopItem->id}");

    $response->assertStatus(200)
        ->assertJson(['message' => 'Shop item deleted successfully']);

    $this->assertDatabaseMissing('shop_items', ['id' => $shopItem->id]);
});

test('validates required fields when creating shop item', function () {
    $response = $this->postJson('/api/shop-items', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['title', 'description', 'price']);
});

test('validates price is numeric and positive when creating shop item', function () {
    $response = $this->postJson('/api/shop-items', [
        'title' => 'Invalid Price Product',
        'price' => -10
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['price']);
});

test('validates category ids exist when creating shop item', function () {
    $response = $this->postJson('/api/shop-items', [
        'title' => 'Product with Invalid Categories',
        'price' => 29.99,
        'category_ids' => [999999, 999998]
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['category_ids.0', 'category_ids.1']);
});

test('returns 404 for non-existent shop item', function () {
    $response = $this->getJson('/api/shop-items/999999');

    $response->assertStatus(404);
});