<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\ShopItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'shop_item_id' => ShopItem::factory(),
            'quantity' => fake()->numberBetween(1, 10),
        ];
    }
}