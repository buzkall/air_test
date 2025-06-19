<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShopItem;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = Customer::all();
        $shopItems = ShopItem::all();

        // Create orders for each customer
        $customers->each(function ($customer) use ($shopItems) {
            // Create 1-3 orders per customer
            $orderCount = rand(1, 3);
            
            for ($i = 0; $i < $orderCount; $i++) {
                $order = Order::factory()->create([
                    'customer_id' => $customer->id,
                ]);

                // Add 1-5 items per order
                $itemCount = rand(1, 5);
                
                for ($j = 0; $j < $itemCount; $j++) {
                    OrderItem::factory()->create([
                        'order_id' => $order->id,
                        'shop_item_id' => $shopItems->random()->id,
                        'quantity' => rand(1, 5),
                    ]);
                }
            }
        });
    }
}