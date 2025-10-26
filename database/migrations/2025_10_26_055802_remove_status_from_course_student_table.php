<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 安全删除 course_student.status 列：
 * - 仅当列存在时才删除，避免 SQLSTATE[42703] Undefined column 错误
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('course_student', function (Blueprint $table) {
            if (Schema::hasColumn('course_student', 'status')) {
                $table->dropColumn('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('course_student', function (Blueprint $table) {
            // 回滚：如果不存在则恢复一个可空的整数列（按你的规范用 int 并加注释）
            if (!Schema::hasColumn('course_student', 'status')) {
                $table->integer('status')->nullable()->comment('报名状态：0=未支付,1=已支付,2=已取消');
            }
        });
    }
};
