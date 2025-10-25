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
        Schema::table('invoices', function (Blueprint $table) {
            // 添加 year_month 字段
            if (!Schema::hasColumn('invoices', 'year_month')) {
                $table->string('year_month', 7)->comment('年月，格式：YYYY-MM')->after('amount');
            }
            
            // 添加 teacher_id 字段（如果不存在）
            if (!Schema::hasColumn('invoices', 'teacher_id')) {
                $table->unsignedBigInteger('teacher_id')->comment('教师ID')->after('course_id');
            }
            
            // 添加 Omise 相关字段
            if (!Schema::hasColumn('invoices', 'omise_charge_id')) {
                $table->string('omise_charge_id', 100)->default('')->comment('Omise返回的charge ID')->after('paid_at');
            }
            
            if (!Schema::hasColumn('invoices', 'omise_source_id')) {
                $table->string('omise_source_id', 100)->default('')->comment('Omise的source或token ID')->after('omise_charge_id');
            }
            
            if (!Schema::hasColumn('invoices', 'omise_last_event_id')) {
                $table->string('omise_last_event_id', 100)->default('')->comment('最近一次Webhook事件ID')->after('omise_source_id');
            }
            
            if (!Schema::hasColumn('invoices', 'payment_method')) {
                $table->string('payment_method', 50)->default('')->comment('支付方式/渠道')->after('omise_last_event_id');
            }
            
            if (!Schema::hasColumn('invoices', 'currency')) {
                $table->string('currency', 10)->default('THB')->comment('币种')->after('payment_method');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'year_month', 
                'teacher_id', 
                'omise_charge_id', 
                'omise_source_id', 
                'omise_last_event_id', 
                'payment_method', 
                'currency'
            ]);
        });
    }
};
