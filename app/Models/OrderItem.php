<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'shop_item_id', 'quantity'];
    /** @use HasFactory<\Database\Factories\OrderItemFactory> */
    use HasFactory;

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function shopItem()
    {
        return $this->belongsTo(ShopItem::class);
    }
}
