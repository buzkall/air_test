<?php

use App\Models\ShopItemCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

test('can list all shop item categories', function () {
    $response = $this->getJson('/api/shop-item-categories');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'description',
                    'created_at',
                    'updated_at',
                    'shop_items'
                ]
            ],
            'links'
        ]);
});

test('can show a specific shop item category', function () {
    $category = ShopItemCategory::first();
    
    $response = $this->getJson("/api/shop-item-categories/{$category->id}");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'id',
            'title',
            'description',
            'created_at',
            'updated_at',
            'shop_items'
        ]);
});

test('can create a shop item category', function () {
    $categoryData = [
        'title' => 'New Category',
        'description' => 'A brand new category'
    ];

    $response = $this->postJson('/api/shop-item-categories', $categoryData);

    $response->assertStatus(201)
        ->assertJsonFragment($categoryData);

    $this->assertDatabaseHas('shop_item_categories', $categoryData);
});

test('validates required description when creating category', function () {
    $categoryData = [
        'title' => 'Category Without Description'
    ];

    $response = $this->postJson('/api/shop-item-categories', $categoryData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['description']);
});

test('can update a shop item category', function () {
    $category = ShopItemCategory::first();
    $updateData = [
        'title' => 'Updated Category',
        'description' => 'Updated description'
    ];

    $response = $this->putJson("/api/shop-item-categories/{$category->id}", $updateData);

    $response->assertStatus(200)
        ->assertJsonFragment($updateData);

    $this->assertDatabaseHas('shop_item_categories', $updateData);
});

test('can partially update a shop item category', function () {
    $category = ShopItemCategory::first();
    $updateData = ['title' => 'Partially Updated'];

    $response = $this->putJson("/api/shop-item-categories/{$category->id}", $updateData);

    $response->assertStatus(200)
        ->assertJsonFragment($updateData);

    $this->assertDatabaseHas('shop_item_categories', [
        'id' => $category->id,
        'title' => 'Partially Updated'
    ]);
});

test('can delete a shop item category', function () {
    $category = ShopItemCategory::first();

    $response = $this->deleteJson("/api/shop-item-categories/{$category->id}");

    $response->assertStatus(200)
        ->assertJson(['message' => 'Category deleted successfully']);

    $this->assertDatabaseMissing('shop_item_categories', ['id' => $category->id]);
});

test('validates required fields when creating category', function () {
    $response = $this->postJson('/api/shop-item-categories', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['title', 'description']);
});

test('validates title length when creating category', function () {
    $response = $this->postJson('/api/shop-item-categories', [
        'title' => str_repeat('a', 256), // Too long
        'description' => 'Valid description'
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['title']);
});

test('returns 404 for non-existent category', function () {
    $response = $this->getJson('/api/shop-item-categories/999999');

    $response->assertStatus(404);
});