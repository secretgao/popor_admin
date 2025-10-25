<?php

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
        // 删除唯一约束
        \DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_username_unique');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 重新添加唯一约束
        \DB::statement('ALTER TABLE users ADD CONSTRAINT users_username_unique UNIQUE (username)');
    }
};
