<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'avatar',
        'category_id',
        'import_price',
        'price',
        'description',
        'display',
        'status',
    ];

    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }

    public function categories(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function productDetail()
    {
        return $this->hasOne(Product_detail::class);
    }

    public function promotions()
    {
        return $this->hasMany(Promotion::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class, 'product_color');
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_size');
    }
}
