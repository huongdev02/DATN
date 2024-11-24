<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'total_amount',
        'payment_method',
        'ship_method',
        'ship_address_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_detail_id');
    }

    public function shipAddress()
    {
        return $this->belongsTo(Ship_address::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(Order_detail::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'order_id');
    }
}
