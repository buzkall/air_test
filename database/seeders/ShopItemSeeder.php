<?php

namespace Database\Seeders;

use App\Models\ShopItem;
use App\Models\ShopItemCategory;
use Illuminate\Database\Seeder;

class ShopItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ShopItemCategory::all();

        // Create shop items and assign random categories
        ShopItem::factory(50)->create()->each(function ($item) use ($categories) {
            $item->categories()->attach(
                $categories->random(rand(1, 3))->pluck('id')->toArray()
            );
        });
    }
}