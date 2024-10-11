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

    public function productDetails()
    {
        return $this->hasMany(Product_detail::class);
    }
}
