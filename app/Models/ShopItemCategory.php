<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopItemCategory extends Model
{
    /** @use HasFactory<\Database\Factories\ShopItemCategoryFactory> */
    use HasFactory;

    public function items()
    {
        return $this->belongsToMany(ShopItem::class);
    }
}
