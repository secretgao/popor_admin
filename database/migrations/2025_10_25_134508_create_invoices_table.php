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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('student_id')->comment('学生ID');
            $table->bigInteger('course_id')->comment('课程ID');
            $table->integer('teacher_id')->nullable()->comment('教师ID');
            $table->decimal('amount', 10, 2)->comment('账单金额');
            $table->integer('status')->default(0)->comment('账单状态：0=待处理, 1=已发送, 2=已支付, 3=已取消');
            $table->timestamp('due_date')->nullable()->comment('到期日期');
            $table->timestamp('sent_at')->nullable()->comment('发送时间');
            $table->timestamp('paid_at')->nullable()->comment('支付时间');
            $table->timestamps();
            
            // 创建索引
            $table->index('student_id', 'idx_invoices_student');
            $table->index('course_id', 'idx_invoices_course');
            $table->index('teacher_id', 'idx_invoices_teacher');
            $table->index('status', 'idx_invoices_status');
            $table->index('due_date', 'idx_invoices_due_date');
            
            // 创建唯一约束
            $table->unique(['student_id', 'course_id'], 'uq_invoice_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
