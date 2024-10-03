<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;
    protected $fillable = [
        'name_color',
    ];

    public function productDetails()
    {
        return $this->hasMany(Product_detail::class);
    }
}
