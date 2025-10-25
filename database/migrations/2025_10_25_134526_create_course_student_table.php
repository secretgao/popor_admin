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
        Schema::create('course_student', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('course_id')->comment('课程ID');
            $table->bigInteger('student_id')->comment('学生ID');
            $table->timestamps();
            
            // 创建索引
            $table->index('course_id', 'idx_course_student_course');
            $table->index('student_id', 'idx_course_student_student');
            
            // 创建唯一约束
            $table->unique(['course_id', 'student_id'], 'uq_course_student_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_student');
    }
};
