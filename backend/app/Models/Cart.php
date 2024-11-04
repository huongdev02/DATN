<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'user_id',
        'quantity',
        'total',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function productDetails()
    {
        return $this->belongsToMany(Product_detail::class, 'cart_items')
            ->withPivot('quantity');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}
