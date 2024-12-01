<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;
    protected $fillable = [
        'size',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function productDetails()
    {
        return $this->hasMany(Product_Detail::class);
    }
}
