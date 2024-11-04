<?php

use App\Models\Order;
use App\Models\User;
use App\Models\Voucher;
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
        Schema::create('voucher_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(); // Đảm bảo foreign key cho user
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade'); // Đảm bảo foreign key cho order
            $table->foreignId('voucher_id')->constrained('vouchers')->onDelete('cascade'); // Đảm bảo foreign key cho voucher
            $table->decimal('discount_value', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voucher_usages');
    }
};
