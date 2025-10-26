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
            // 支付结果相关字段
            $table->boolean('payment_success')->nullable()->comment('支付是否成功');
            $table->string('payment_status', 50)->nullable()->comment('支付状态，如 successful, failed');
            $table->string('payment_transaction_id', 100)->nullable()->comment('支付交易ID');
            $table->text('payment_error_message')->nullable()->comment('支付错误信息');
            $table->timestamp('payment_processed_at')->nullable()->comment('支付处理时间');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'payment_success',
                'payment_status', 
                'payment_transaction_id',
                'payment_error_message',
                'payment_processed_at'
            ]);
        });
    }
};
