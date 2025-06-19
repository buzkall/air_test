<?php

namespace Tests\Feature;

use App\Models\ShopItem;
use App\Models\ShopItemCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopItemApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_shop_items()
    {
        ShopItem::factory()->count(3)->create();
        $response = $this->getJson('/api/shop-items');
        $response->assertStatus(200)->assertJsonCount(3, 'data');
    }

    public function test_can_create_shop_item()
    {
        $category = ShopItemCategory::factory()->create();
        $data = [
            'title' => 'Phone',
            'description' => 'Smartphone',
            'price' => 199.99,
            'categories' => [$category->id],
        ];
        $response = $this->postJson('/api/shop-items', $data);
        $response->assertStatus(201)->assertJsonFragment([
            'title' => 'Phone',
            'description' => 'Smartphone',
            'price' => 199.99,
        ]);
        $this->assertDatabaseHas('shop_items', [
            'title' => 'Phone',
            'description' => 'Smartphone',
            'price' => 199.99,
        ]);
    }

    public function test_can_show_shop_item()
    {
        $shopItem = ShopItem::factory()->create();
        $response = $this->getJson('/api/shop-items/' . $shopItem->id);
        $response->assertStatus(200)->assertJsonFragment([
            'id' => $shopItem->id,
            'title' => $shopItem->title,
        ]);
    }

    public function test_can_update_shop_item()
    {
        $shopItem = ShopItem::factory()->create();
        $category = ShopItemCategory::factory()->create();
        $data = [
            'title' => 'Updated',
            'description' => 'Updated desc',
            'price' => 299.99,
            'categories' => [$category->id],
        ];
        $response = $this->putJson('/api/shop-items/' . $shopItem->id, $data);
        $response->assertStatus(200)->assertJsonFragment([
            'title' => 'Updated',
            'description' => 'Updated desc',
            'price' => 299.99,
        ]);
        $this->assertDatabaseHas('shop_items', [
            'id' => $shopItem->id,
            'title' => 'Updated',
            'description' => 'Updated desc',
            'price' => 299.99,
        ]);
    }

    public function test_can_delete_shop_item()
    {
        $shopItem = ShopItem::factory()->create();
        $response = $this->deleteJson('/api/shop-items/' . $shopItem->id);
        $response->assertStatus(204);
        $this->assertDatabaseMissing('shop_items', ['id' => $shopItem->id]);
    }
}
