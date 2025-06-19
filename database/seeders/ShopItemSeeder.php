<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShopItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\ShopItem::factory(20)->create()->each(function ($item) {
            $categories = \App\Models\ShopItemCategory::inRandomOrder()->take(rand(1, 3))->pluck('id');
            $item->categories()->attach($categories);
        });
    }
}
