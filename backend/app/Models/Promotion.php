<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'start_day',
        'end_day',
        'price_discount',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
