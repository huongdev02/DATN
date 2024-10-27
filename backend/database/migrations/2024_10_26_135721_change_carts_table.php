<?php

use App\Models\Product;
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
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeignIdFor(Product::class);
            $table->dropColumn('product_id');
            $table->dropColumn('quantity');
            $table->dropColumn('total');
            $table->unique(['user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->foreignIdFor(Product::class)->constrained();
            $table->unsignedInteger('quantity');
            $table->decimal('total', 10, 2);
        });
    }
};
