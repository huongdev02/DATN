<?php

use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Support\Facades\DB;

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
        Schema::create('product_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Product::class)->constrained();
            $table->foreignIdFor(Size::class)->constrained();
            $table->foreignIdFor(Color::class)->constrained();
            $table->timestamps();
        });
        DB::statement('ALTER TABLE `product_details` ADD CONSTRAINT `check_quantity_productdetail` CHECK (`quantity` >= 0)'); //dkien: số lượng > 0
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_details');
    }
};
