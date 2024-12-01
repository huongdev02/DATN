<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'type',
        'description',
        'discount_value',
        'discount_min',
        'max_discount',
        'min_order_count',
        'max_order_count',
        'quantity',
        'used_times',
        'status',
        'start_day',
        'end_day'
    ];

    public function voucherUsages()
    {
        return $this->hasMany(Voucher_usage::class);
    }
}
