<?php

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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();

            $table->string('code', 10)->unique();
            $table->tinyInteger('type')->default(0); //0 là giá trị có định, 1 là triết khấu %
            $table->decimal('discount_value', 10, 2);
            $table->text('description')->nullable();
            $table->decimal('discount_min', 10, 2)->default(0);
            $table->decimal('max_discount', 10, 2)->default(0); 
            $table->unsignedInteger('min_order_count')->default(1);
            $table->unsignedInteger('max_order_count')->default(1);
            $table->integer('quantity')->default(1);
            $table->integer('used_times')->default(0); 
            $table->dateTime('start_day')->nullable(); 
            $table->dateTime('end_day')->nullable();
            $table->tinyInteger('status')->default(1);//0: Không hoạt động, 1: Đang hoạt động, 2: hết, 3: Chờ phát hành
            $table->timestamps();
        });
        DB::statement('ALTER TABLE `vouchers` ADD CONSTRAINT `check_type` CHECK (`type` >= 0 AND `type` <= 1)');
        DB::statement('ALTER TABLE `vouchers` ADD CONSTRAINT `check_max_order_count` CHECK (`max_order_count` >=  `min_order_count` )');
        DB::statement('ALTER TABLE `vouchers` ADD CONSTRAINT `check_end_day` CHECK (`end_day` >=  `start_day` )');
        DB::statement('ALTER TABLE `vouchers` ADD CONSTRAINT `check_status_voucher` CHECK (`status` >= 0 AND `status` <= 3)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
