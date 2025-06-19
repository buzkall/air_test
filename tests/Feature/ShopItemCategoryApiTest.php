<?php

namespace Tests\Feature;

use App\Models\ShopItemCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopItemCategoryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_categories()
    {
        ShopItemCategory::factory()->count(3)->create();
        $response = $this->getJson('/api/shop-item-categories');
        $response->assertStatus(200)->assertJsonCount(3, 'data');
    }

    public function test_can_create_category()
    {
        $data = [
            'title' => 'Electronics',
            'description' => 'All electronic items',
        ];
        $response = $this->postJson('/api/shop-item-categories', $data);
        $response->assertStatus(201)->assertJsonFragment($data);
        $this->assertDatabaseHas('shop_item_categories', $data);
    }

    public function test_can_show_category()
    {
        $category = ShopItemCategory::factory()->create();
        $response = $this->getJson('/api/shop-item-categories/' . $category->id);
        $response->assertStatus(200)->assertJsonFragment([
            'id' => $category->id,
            'title' => $category->title,
        ]);
    }

    public function test_can_update_category()
    {
        $category = ShopItemCategory::factory()->create();
        $data = ['title' => 'Updated', 'description' => 'Updated desc'];
        $response = $this->putJson('/api/shop-item-categories/' . $category->id, $data);
        $response->assertStatus(200)->assertJsonFragment($data);
        $this->assertDatabaseHas('shop_item_categories', $data);
    }

    public function test_can_delete_category()
    {
        $category = ShopItemCategory::factory()->create();
        $response = $this->deleteJson('/api/shop-item-categories/' . $category->id);
        $response->assertStatus(204);
        $this->assertDatabaseMissing('shop_item_categories', ['id' => $category->id]);
    }
}
