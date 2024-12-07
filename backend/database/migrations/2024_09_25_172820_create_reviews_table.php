<?php

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Order::class)->constrained();
            $table->foreignIdFor(User::class)->constrained();
            $table->foreignIdFor(Product::class)->constrained();
            $table->string('image_path')->nullable();
            $table->tinyInteger('rating')->default(5);//thang 5 điểm 
            $table->text('comment')->nullable();
            $table->boolean('is_reviews')->default(1);
            $table->timestamps();
        });
        DB::statement('ALTER TABLE `reviews` ADD CONSTRAINT `check_rating` CHECK (`rating` >= 0 AND `rating` <= 5)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
