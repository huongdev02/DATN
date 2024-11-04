<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_detail extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'size_id',
        'color_id',
        'quantity',
        'total',
        'sell_quantity',
        'number_statictis',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function cart()
    {
        return $this->belongsToMany(Cart::class, 'cart_items')
            ->wherePivot('quantity');
    }

    public function orderDetails()
    {
        return $this->hasMany(Order_detail::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}
