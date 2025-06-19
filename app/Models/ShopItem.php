<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopItem extends Model
{
    protected $fillable = ['title', 'description', 'price'];
    /** @use HasFactory<\Database\Factories\ShopItemFactory> */
    use HasFactory;

    public function categories()
    {
        return $this->belongsToMany(ShopItemCategory::class);
    }
}
