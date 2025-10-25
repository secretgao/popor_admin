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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->comment('用户名');
        });
        
        // 为现有用户生成用户名
        \DB::statement('UPDATE users SET username = CONCAT(\'user_\', id) WHERE username IS NULL');
        
        // 添加唯一约束
        \DB::statement('ALTER TABLE users ALTER COLUMN username SET NOT NULL');
        \DB::statement('ALTER TABLE users ADD CONSTRAINT users_username_unique UNIQUE (username)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
        });
    }
};
