<?php

use App\Models\Category;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('name')->unique();
            $table->string('avatar');
            $table->foreignIdFor(Category::class)->constrained();
            $table->decimal('import_price', 10, 2);
            $table->decimal('price', 10, 2);
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('sell_quantity')->default(0);
            $table->unsignedInteger('view')->default(0);
            $table->text('description')->nullable();
            $table->tinyInteger('display')->default(1); // 1(true), 0(false), cho phép kiểm soát việc hiển thị sản phẩm trên website
            $table->tinyInteger('status')->default(1); // 0: Không hoạt động, 1: Đang mở bán, 2: Ngừng bán, 3: Chờ duyệt

            $table->timestamps();
        });

        DB::statement('ALTER TABLE `products` ADD CONSTRAINT `check_price` CHECK (`price` >= `import_price`)'); //dkien: giá bán phải lớn hơn giá nhập
        DB::statement('ALTER TABLE `products` ADD CONSTRAINT `check_status_products` CHECK (`status` >= 0 AND `status` <= 3)'); //dkien: chỉ nhận 0 1 2 3
        DB::statement('ALTER TABLE `products` ADD CONSTRAINT `check_quantity` CHECK (`quantity` >= 1)'); //dkien: sl>1
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
