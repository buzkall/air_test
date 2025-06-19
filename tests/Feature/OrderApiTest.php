<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Customer;
use App\Models\ShopItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_orders()
    {
        Order::factory()->count(2)->create();
        $response = $this->getJson('/api/orders');
        $response->assertStatus(200)->assertJsonCount(2, 'data');
    }

    public function test_can_create_order()
    {
        $customer = Customer::factory()->create();
        $item1 = ShopItem::factory()->create();
        $item2 = ShopItem::factory()->create();
        $data = [
            'customer_id' => $customer->id,
            'items' => [
                ['shop_item_id' => $item1->id, 'quantity' => 2],
                ['shop_item_id' => $item2->id, 'quantity' => 1],
            ],
        ];
        $response = $this->postJson('/api/orders', $data);
        $response->assertStatus(201)->assertJsonFragment(['customer_id' => $customer->id]);
        $this->assertDatabaseHas('orders', ['customer_id' => $customer->id]);
    }

    public function test_can_show_order()
    {
        $order = Order::factory()->create();
        $response = $this->getJson('/api/orders/' . $order->id);
        $response->assertStatus(200)->assertJsonFragment([
            'id' => $order->id,
            'customer_id' => $order->customer_id,
        ]);
    }

    public function test_can_update_order()
    {
        $order = Order::factory()->create();
        $customer = Customer::factory()->create();
        $data = ['customer_id' => $customer->id];
        $response = $this->putJson('/api/orders/' . $order->id, $data);
        $response->assertStatus(200)->assertJsonFragment(['customer_id' => $customer->id]);
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'customer_id' => $customer->id]);
    }

    public function test_can_delete_order()
    {
        $order = Order::factory()->create();
        $response = $this->deleteJson('/api/orders/' . $order->id);
        $response->assertStatus(204);
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }
}
