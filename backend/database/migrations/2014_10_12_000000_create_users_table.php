<?php

use App\Models\Ship_address;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('avatar')->nullable();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('fullname')->nullable(); //sau khi dki xong có cập nhật tên hiển thị
            $table->date('birth_day')->nullable();
            $table->string('phone', 15)->nullable(); 
            $table->string('email')->unique()->nullable();
            $table->string('address')->nullable();
            $table->tinyInteger('role')->default(0); //role: 0 user, 1 nhân viên, 2admin
            
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
        DB::statement('ALTER TABLE `users` ADD CONSTRAINT `check_role` CHECK (`role` >= 0 AND `role` <= 2)'); //dkien: chỉ nhận 0 1 và 2
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
