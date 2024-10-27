<?php

use App\Models\Product;
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
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropForeignIdFor(Product::class);
            $table->dropColumn('product_id');
            $table->foreignIdFor(Product_detail::class)->nullable()->constrained();
            $table->string('name_product', 255);
            $table->string('size', 50);
            $table->string('color', 50);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->foreignIdFor(Product::class)->constrained();
            $table->dropForeignIdFor(Product_detail::class);
            $table->dropColumn('product_detail_id');
            $table->dropColumn('name_product');
            $table->dropColumn('size');
            $table->dropColumn('color');
        });
    }
};
