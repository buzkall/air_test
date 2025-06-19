<?php

namespace Database\Seeders;

use App\Models\ShopItemCategory;
use Illuminate\Database\Seeder;

class ShopItemCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['title' => 'Electronics', 'description' => 'Electronic devices and gadgets'],
            ['title' => 'Clothing', 'description' => 'Fashion and apparel items'],
            ['title' => 'Books', 'description' => 'Books and educational materials'],
            ['title' => 'Home & Garden', 'description' => 'Home improvement and garden supplies'],
            ['title' => 'Sports', 'description' => 'Sports equipment and accessories'],
        ];

        foreach ($categories as $category) {
            ShopItemCategory::create($category);
        }

        // Create additional random categories
        ShopItemCategory::factory(5)->create();
    }
}