<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_detail_id',
        'quantity',
    ];

    // Liên kết đến bảng Cart
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    // Liên kết đến bảng ProductDetail
    public function productDetail()
    {
        return $this->belongsTo(Product_detail::class);
    }
}

