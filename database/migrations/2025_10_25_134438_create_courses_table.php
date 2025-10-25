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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200)->comment('课程名');
            $table->char('year_month', 6)->comment('课程年月：yyyyMM（如202510）');
            $table->decimal('fee', 10, 2)->default(0)->comment('课程费用');
            $table->bigInteger('teacher_id')->comment('授课教师用户ID');
            $table->timestamp('deleted_at')->nullable()->comment('软删除时间');
            $table->boolean('is_del')->default(false)->comment('是否删除');
            $table->timestamps();
            
            // 创建索引
            $table->index('deleted_at', 'idx_courses_deleted_at');
            $table->index('teacher_id', 'idx_courses_teacher');
            $table->index('year_month', 'idx_courses_year_month');
            
            // 创建唯一约束
            $table->unique(['teacher_id', 'year_month', 'name'], 'uq_courses_teacher_month_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
