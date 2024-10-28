<?php

use App\Models\Product;
use App\Models\Ship_address;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained();
            $table->foreignIdFor(Product::class)->constrained();
            $table->unsignedInteger('quantity');
            $table->decimal('total_amount', 10, 2); //0: Đang chờ xử lí, 1: Đã xử lí/ đang chuẩn bị sản phẩm, 2: Đang vận chuyển, 3: Giao hàng thành công, 4: Đơn hàng đã bị hủy, 5: Đơn hàng đã được trả lại bởi người dung
            $table->foreignId('payment_method_id')->constrained('payment_method')->onDelete('cascade');
            $table->tinyInteger('ship_method')->default(1); // 0: giao hàng tiêu chuẩn, 1: giao hàng hỏa tốc

            $table->foreignIdFor(Ship_address::class)->constrained();
            $table->enum('status', ['pending', 'completed', 'canceled', 'refunded']);
            $table->timestamps();
        });
        DB::statement('ALTER TABLE `orders` ADD CONSTRAINT `check_payment_method` CHECK (`payment_method` >= 0 AND `payment_method` <= 2)');
        DB::statement('ALTER TABLE `orders` ADD CONSTRAINT `check_ship_method` CHECK (`ship_method` >= 0 AND `ship_method` <= 1)');
        DB::statement('ALTER TABLE `orders` ADD CONSTRAINT `check_status_orders` CHECK (`status` >= 0 AND `status` <= 5)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
