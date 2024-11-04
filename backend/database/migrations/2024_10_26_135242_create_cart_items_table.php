<?php

use App\Models\Cart;
use App\Models\Product_detail;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            // $table->id();
            $table->foreignIdFor(Cart::class)->constrained();
            $table->foreignIdFor(Product_detail::class)->constrained();
            $table->unsignedBigInteger('quantity');
            $table->primary(['cart_id', 'product_detail_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
