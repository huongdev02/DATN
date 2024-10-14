<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'staff_id',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
