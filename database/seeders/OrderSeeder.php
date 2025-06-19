<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Order::factory(10)->create()->each(function ($order) {
            $shopItems = \App\Models\ShopItem::inRandomOrder()->take(rand(1, 5))->get();
            foreach ($shopItems as $shopItem) {
                $order->items()->create([
                    'shop_item_id' => $shopItem->id,
                    'quantity' => rand(1, 5)
                ]);
            }
        });
    }
}
